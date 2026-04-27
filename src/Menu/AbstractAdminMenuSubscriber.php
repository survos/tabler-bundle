<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Doctrine\Persistence\ManagerRegistry;
use Survos\FieldBundle\Registry\EntityMetaRegistry;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\Routing\RouterInterface;

/**
 * Base for bundle admin-navbar menu subscribers.
 *
 * Subclasses declare their bundle label and ApiResource entity classes.
 * Each entity gets a menu item → survos_admin_browse with:
 *   - icon resolved from #[EntityMeta] via EntityMetaRegistry (or fallback reflection)
 *   - record count badge (requires Doctrine in the container)
 *
 * Usage:
 *   class AiBatchMenuSubscriber extends AbstractAdminMenuSubscriber
 *   {
 *       protected function getLabel(): string { return 'AI Batch'; }
 *       protected function getResourceClasses(): array { return [AiBatch::class]; }
 *
 *       #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU)]
 *       public function onAdminNavbarMenu(MenuEvent $event): void
 *       {
 *           $this->buildAdminMenu($event);
 *       }
 *   }
 */
abstract class AbstractAdminMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        protected readonly ?RouterInterface      $router              = null,
        protected readonly ?RouteAliasService    $routeAliasService   = null,
        protected readonly ?IconService          $iconService         = null,
        protected readonly ?ManagerRegistry      $managerRegistry     = null,
        protected readonly ?EntityMetaRegistry   $entityMetaRegistry  = null,
    ) {}

    abstract protected function getLabel(): string;

    /**
     * Entity FQCN list, optionally keyed by display label.
     *   [AiBatch::class]                → label from short class name
     *   ['Batches' => AiBatch::class]   → explicit label
     *
     * @return array<string|int, class-string>
     */
    abstract protected function getResourceClasses(): array;

    /** Override to set the submenu's own icon. */
    protected function getGroupIcon(): ?string
    {
        return null;
    }

    protected function buildAdminMenu(MenuEvent $event): void
    {
        $classes = $this->getResourceClasses();
        if (empty($classes)) {
            return;
        }

        $menu    = $event->getMenu();
        $submenu = $this->addSubmenu($menu, $this->getLabel(), $this->getGroupIcon());

        foreach ($classes as $labelOrIndex => $class) {
            $label = is_string($labelOrIndex)
                ? $labelOrIndex
                : (new \ReflectionClass($class))->getShortName();

            $this->add(
                $submenu,
                'survos_admin_browse',
                ['class' => $class],
                $label,
                icon:  $this->resolveEntityIcon($class),
                badge: $this->resolveCount($class),
            );
        }
    }

    private function resolveEntityIcon(string $class): ?string
    {
        if ($this->entityMetaRegistry) {
            return $this->entityMetaRegistry->get($class)?->icon;
        }

        // Fallback: read #[EntityMeta] via reflection if registry not injected
        if (class_exists(\Survos\FieldBundle\Attribute\EntityMeta::class)) {
            $attrs = (new \ReflectionClass($class))->getAttributes(\Survos\FieldBundle\Attribute\EntityMeta::class);
            if ($attrs) {
                return $attrs[0]->newInstance()->icon;
            }
        }

        return null;
    }

    private function resolveCount(string $class): ?string
    {
        if (!$this->managerRegistry) {
            return null;
        }

        try {
            $manager = $this->managerRegistry->getManagerForClass($class);
            $count   = $manager?->getRepository($class)->count([]);
            if ($count === null) {
                return null;
            }
            return (new \NumberFormatter('en', \NumberFormatter::DECIMAL_COMPACT_SHORT))->format($count);
        } catch (\Throwable) {
            return null;
        }
    }
}
