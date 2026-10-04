<?php
/* src/Twig/MenuExtension.php v1.0 - Twig functions for menu rendering */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\MenuContext;
use Survos\TablerBundle\Service\MenuRenderer;
use Survos\TablerBundle\Service\NavigationOrigin;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MenuExtension extends AbstractExtension
{
    public function __construct(
        private readonly MenuRenderer $renderer,
        private readonly MenuContext $menuContext,
        private readonly RequestStack $requestStack,
        private readonly bool $debugMenuSlotsEnabled = false,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tabler_menu', [$this, 'renderMenu'], ['is_safe' => ['html'], 'needs_context' => true]),
            new TwigFunction('tabler_menu_has_items', [$this, 'hasItemsInTemplate'], ['needs_context' => true]),
            new TwigFunction('tabler_menu_options', [$this, 'setMenuOptions'], ['is_safe' => ['html']]),
            new TwigFunction('nav_origin', [NavigationOrigin::class, 'encode']),
            new TwigFunction('tabler_menu_context_summary', [$this, 'getMenuContextSummary']),
            new TwigFunction('tabler_menu_debug_enabled', [$this, 'isDebugEnabled']),
            new TwigFunction('tabler_menu_slot_states', [$this, 'getSlotStates']),
        ];
    }

    /**
     * {{ tabler_menu('NAVBAR_END') }}, {{ tabler_menu(PAGE_ACTIONS, {project: project}) }}.
     * $options reach the listeners; $render overrides how it is drawn (template, rootAttributes, ...).
     *
     * Listeners also see, without being passed anything: the template's own variables that are objects (a page that
     * renders `publication` gives every menu `getOption('publication')`), and the controller's resolved arguments
     * (AmbientMenuContext). Precedence, lowest to highest: config menu_options, controller arguments,
     * template variables, tabler_menu_options(), this call's $options.
     *
     * @param array<string, mixed> $context the calling template's variables (Twig supplies them)
     */
    public function renderMenu(array $context, string $slot, array $options = [], array $render = []): string
    {
        return $this->renderer->render($slot, $this->withAmbient($context, $options), $render);
    }

    public function hasItemsInTemplate(array $context, string $slot, array $options = []): bool
    {
        return $this->renderer->hasItems($slot, $this->withAmbient($context, $options));
    }

    /**
     * Objects only: the entities a page is about. Scalars in a template context are too ambiguous to be options,
     * and framework objects (app, closures, markup) are not entities.
     *
     * @return array<string, mixed>
     */
    private function withAmbient(array $context, array $options): array
    {
        $ambient = [];
        foreach ($context as $key => $value) {
            if (!is_string($key) || $key === '' || $key[0] === '_' || !is_object($value)) {
                continue;
            }
            if ($value instanceof \Closure || $value instanceof \Twig\Markup || $value instanceof \Symfony\Bridge\Twig\AppVariable) {
                continue;
            }
            $ambient[$key] = $value;
        }

        return array_merge($ambient, $this->menuContext->getOptions(), $options);
    }

    public function hasItems(string $slot, array $options = []): bool
    {
        return $this->renderer->hasItems($slot, $options);
    }

    public function setMenuOptions(array $options = [], mixed $caller = null): string
    {
        if ($caller !== null) {
            $options['caller'] = is_scalar($caller) ? (string) $caller : get_debug_type($caller);
        }

        $this->menuContext->addOptions($options);

        return '';
    }

    /**
     * @return array<string, string>
     */
    public function getMenuContextSummary(): array
    {
        $summary = [];

        foreach ($this->menuContext->getOptions() as $key => $value) {
            if (!is_string($key) || $value === null) {
                continue;
            }

            $formatted = $this->formatMenuOptionValue($value);
            if ($formatted === null) {
                continue;
            }

            $summary[$key] = $formatted;
        }

        return $summary;
    }

    public function isDebugEnabled(): bool
    {
        // The /debug-menu page sets the dummy_menu option; show the outline there too.
        if (!empty($this->menuContext->getOptions()['dummy_menu'])) {
            return true;
        }

        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return $this->debugMenuSlotsEnabled;
        }

        return $this->debugMenuSlotsEnabled || $request->query->getBoolean('debugMenuSlots');
    }

    /**
     * @return list<array{slot:string, area:string, hasItems:bool}>
     */
    public function getSlotStates(): array
    {
        $states = [];

        foreach (MenuEvent::getConstants() as $slot) {
            $states[] = [
                'slot' => $slot,
                'area' => self::BASE_LAYOUT_AREAS[$slot] ?? 'custom',
                'hasItems' => $this->hasItems($slot),
            ];
        }

        return $states;
    }

    private const BASE_LAYOUT_AREAS = [
        MenuEvent::BANNER => 'banner',
        MenuEvent::NAVBAR_START => 'top nav / left',
        MenuEvent::NAVBAR_THEME => 'top nav / right',
        MenuEvent::NAVBAR_NOTIFICATIONS => 'top nav / right',
        MenuEvent::NAVBAR_APPS => 'top nav / right',
        MenuEvent::NAVBAR_LANGUAGE => 'top nav / right',
        MenuEvent::NAVBAR_END => 'top nav / right',
        MenuEvent::SEARCH => 'top nav / right',
        MenuEvent::AUTH => 'top nav / right',
        MenuEvent::NAVBAR_MENU => 'secondary nav / main',
        MenuEvent::NAVBAR_MENU_END => 'secondary nav / right',
        MenuEvent::BREADCRUMB => 'page header / breadcrumb',
        MenuEvent::PAGE_ACTIONS => 'page header / actions',
        MenuEvent::PAGE_NAV => 'page header / tabs',
        MenuEvent::SIDEBAR => 'page body / sidebar',
        MenuEvent::FOOTER => 'footer / left',
        MenuEvent::FOOTER_END => 'footer / right',
    ];

    private function formatMenuOptionValue(mixed $value): ?string
    {
        if (is_scalar($value)) {
            return (string) $value;
        }

        if (is_object($value)) {
            $shortName = (new \ReflectionClass($value))->getShortName();
            $stringValue = $this->stringifyObject($value);

            return $stringValue !== null
                ? sprintf('%s: %s', $shortName, $stringValue)
                : $shortName;
        }

        if (is_array($value)) {
            return sprintf('array[%d]', count($value));
        }

        return null;
    }

    private function stringifyObject(object $value): ?string
    {
        if (!method_exists($value, '__toString')) {
            return null;
        }

        try {
            $stringValue = trim((string) $value);
        } catch (\Throwable) {
            return null;
        }

        return $stringValue !== '' ? $stringValue : null;
    }
}
