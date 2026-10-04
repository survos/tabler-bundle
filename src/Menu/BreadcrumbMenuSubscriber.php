<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Survos\FieldBundle\Enum\Purpose;
use Survos\FieldBundle\Model\RouteMetaDescriptor;
use Survos\FieldBundle\Registry\RouteMetaRegistry;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\MenuDispatcher;
use Survos\TablerBundle\Service\NavigationOrigin;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Exception\ExceptionInterface as RoutingException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Builds the BREADCRUMB trail so no app writes a breadcrumb listener. A trail is up to three parts:
 *
 * 1. **Where you came from.** If the URL carries an `in` token (NavigationOrigin: the list's own path and query) or a
 *    plain `returnTo` path on this site, it is the first crumb, labelled by the page it points at (that route's #[RouteMeta(label:)]). This is the Flickr
 *    idea: the same page shows a different way back depending on whether you arrived from a search, an album or a
 *    stream, and because the origin is in the URL, a bookmark keeps it.
 * 2. **What the page is about.** The ordered entities the menu already receives (see AmbientMenuContext), each
 *    becoming a crumb when its class has a route declared as its page:
 *
 *        #[Route('/{publication}', name: 'ink_publication')]
 *        #[RouteMeta(description: 'Browse a paper\'s issues.', entity: Publication::class, purpose: Purpose::Show)]
 *
 *    The route is the link (its variables are filled from the current URL), `description` the hover title, and the
 *    entity's title/label/name/__toString() the text. The page itself is the last, unlinked crumb.
 * 3. **Where the page sits in the menus.** With no entities, the trail is the ancestors of the current menu item:
 *    Collaboration › Virginia archives when that item is in the Collaboration dropdown.
 *
 * Off unless survos_tabler.auto_breadcrumbs is true, and it stands aside when another listener already filled the
 * slot. A trail that would be only the current page is not drawn.
 */
final class BreadcrumbMenuSubscriber
{
    use MenuBuilderTrait;

    /** Slots searched for the current page when no entity describes it. */
    private const NAVIGATION_SLOTS = [MenuEvent::NAVBAR_MENU, MenuEvent::NAVBAR_MENU_END, MenuEvent::PAGE_NAV, MenuEvent::SIDEBAR];

    public function __construct(
        private readonly RouteMetaRegistry $routeMeta,
        private readonly RequestStack $requests,
        private readonly bool $enabled = false,
        protected readonly ?RouterInterface $router = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService $iconService = null,
        private readonly ?MenuDispatcher $dispatcher = null,
        private readonly ?MatcherInterface $matcher = null,
        private readonly string $originParameter = 'returnTo',
    ) {}

    #[AsEventListener(event: MenuEvent::BREADCRUMB, priority: -100)]
    public function breadcrumb(MenuEvent $event): void
    {
        $menu = $event->getMenu();
        $request = $this->requests->getMainRequest();
        if (!$this->enabled || $menu->hasChildren() || $request === null || $this->router === null) {
            return;
        }

        $currentRoute = (string) $request->attributes->get('_route');
        $routeParams = (array) $request->attributes->get('_route_params', []);
        $currentMeta = $this->routeMeta->get($currentRoute);

        // `in` is the token a list page puts on its links; `returnTo` is the plain-path form for hand-written links.
        $token = (string) $request->query->get(NavigationOrigin::PARAMETER, '');
        $origin = $this->origin($token !== '' ? (NavigationOrigin::decode($token) ?? '') : (string) $request->query->get($this->originParameter, ''));
        $entities = $this->entityTrail($event->options);

        $last = $entities === [] ? null : $entities[array_key_last($entities)];
        $lastIsCurrent = $last !== null && $last[1]->name === $currentRoute;
        $currentLabel = $lastIsCurrent ? null : ($event->getOption('crumb') ?? $currentMeta?->label);

        $ancestors = [];
        if ($entities === []) {
            $ancestors = $this->menuAncestors($event->options);
        }

        $count = ($origin !== null ? 1 : 0) + count($entities) + count($ancestors) + ($currentLabel !== null && $ancestors === [] ? 1 : 0);
        if ($count < 2) {
            return;
        }

        if ($origin !== null) {
            $item = $menu->addChild('origin', ['uri' => $origin['uri'], 'label' => $origin['label']]);
            $item->setExtra('icon', 'tabler:arrow-left');
            $item->setLinkAttribute('title', 'Where you came from');
        }

        foreach ($entities as $i => [$entity, $page]) {
            $label = $this->labelFor($entity);
            if ($lastIsCurrent && $i === array_key_last($entities)) {
                $this->current($menu, $label, $page);
                return;
            }
            $item = $this->add($menu, $page->name, $this->paramsFor($page, $routeParams), label: $label, translationDomain: false, inferIcon: false);
            $item->setLinkAttribute('title', $page->description);
        }

        foreach ($ancestors as $i => $ancestor) {
            if ($i === array_key_last($ancestors)) {
                $this->current($menu, (string) $ancestor->getLabel(), $currentMeta);
                return;
            }
            $crumb = $menu->addChild('ancestor_'.$i, ['label' => $ancestor->getLabel()] + ($ancestor->getUri() ? ['uri' => $ancestor->getUri()] : []));
            $crumb->setExtra('translation_domain', false);
        }

        if ($currentLabel !== null) {
            $this->current($menu, (string) $currentLabel, $currentMeta);
        }
    }

    /** @return array{uri: string, label: string}|null a same-site path the visitor came from, with the page's name */
    private function origin(string $value): ?array
    {
        if (!NavigationOrigin::safe($value)) {
            return null;
        }
        try {
            $match = $this->router?->match(parse_url($value, PHP_URL_PATH) ?: '/');
        } catch (RoutingException) {
            return null;
        }

        return ['uri' => $value, 'label' => $this->routeMeta->get((string) ($match['_route'] ?? ''))?->label ?? 'Back'];
    }

    /**
     * @param array<string, mixed> $options
     * @return list<array{0: object, 1: RouteMetaDescriptor}>
     */
    private function entityTrail(array $options): array
    {
        // One crumb per page route. Several objects of one class (a template often holds the same entity under two
        // names) collapse to the one whose option name is a variable of that route, else the first.
        $chosen = [];
        foreach ($options as $key => $value) {
            if (!is_object($value)) {
                continue;
            }
            $page = $this->routeMeta->forEntityPurpose($value::class, Purpose::Show);
            if ($page === null) {
                continue;
            }
            $named = in_array($key, $this->variablesOf($page), true);
            if (!isset($chosen[$page->name]) || ($named && !$chosen[$page->name][2])) {
                $chosen[$page->name] = [$value, $page, $named];
            }
        }

        return array_values(array_map(static fn (array $c): array => [$c[0], $c[1]], $chosen));
    }

    /**
     * The chain from a top-level menu entry down to the item for this page, e.g. [Collaboration, Virginia archives].
     *
     * @param array<string, mixed> $options
     * @return list<ItemInterface>
     */
    private function menuAncestors(array $options): array
    {
        if ($this->dispatcher === null || $this->matcher === null) {
            return [];
        }
        foreach (self::NAVIGATION_SLOTS as $slot) {
            $found = $this->findCurrent($this->dispatcher->dispatch($slot, $options), []);
            if ($found !== null) {
                // A dropdown whose first entry repeats its own name would read "Collaboration › Collaboration".
                $labels = array_map(static fn (ItemInterface $i): string => (string) $i->getLabel(), $found);

                return array_values(array_filter($found, static fn ($_, int $k): bool => ($labels[$k + 1] ?? null) !== $labels[$k], ARRAY_FILTER_USE_BOTH));
            }
        }

        return [];
    }

    /**
     * @param list<ItemInterface> $path
     * @return list<ItemInterface>|null
     */
    private function findCurrent(ItemInterface $parent, array $path): ?array
    {
        foreach ($parent->getChildren() as $child) {
            $here = [...$path, $child];
            $deeper = $this->findCurrent($child, $here);
            if ($deeper !== null) {
                return $deeper;
            }
            if ($child->getUri() !== null && $this->matcher->isCurrent($child)) {
                return $here;
            }
        }

        return null;
    }

    private function current(ItemInterface $menu, string $label, ?RouteMetaDescriptor $meta): void
    {
        $item = $menu->addChild('current', ['label' => $label]);
        $item->setCurrent(true);
        $item->setExtra('translation_domain', false);
        if ($meta !== null) {
            $item->setAttribute('title', $meta->description);
        }
    }

    /** @return array<string, mixed> only the route's own variables, taken from the current URL */
    private function paramsFor(RouteMetaDescriptor $page, array $routeParams): array
    {
        return array_intersect_key($routeParams, array_flip($this->variablesOf($page)));
    }

    /** @return list<string> */
    private function variablesOf(RouteMetaDescriptor $page): array
    {
        return $this->router?->getRouteCollection()->get($page->name)?->compile()->getVariables() ?? [];
    }

    private function labelFor(object $entity): string
    {
        foreach (['title', 'label', 'name'] as $property) {
            $value = $entity->$property ?? null;
            if (is_scalar($value) && (string) $value !== '') {
                return (string) $value;
            }
        }

        return $entity instanceof \Stringable ? (string) $entity : (new \ReflectionClass($entity))->getShortName();
    }
}
