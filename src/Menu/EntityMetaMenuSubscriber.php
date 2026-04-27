<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Doctrine\Persistence\ManagerRegistry;
use Survos\FieldBundle\Registry\EntityMetaRegistry;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

/**
 * Auto-populates the admin navbar from all #[EntityMeta]-annotated entities.
 * Replaces per-bundle AbstractAdminMenuSubscriber subclasses.
 *
 * Groups become submenus; within each group entities are ordered by EntityMeta::$order.
 * Only entities with adminBrowsable: true and a registered GetCollection route appear.
 */
final class EntityMetaMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        private readonly EntityMetaRegistry  $registry,
        private readonly ?ManagerRegistry    $managerRegistry  = null,
        protected readonly ?RouterInterface  $router           = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService      $iconService      = null,
    ) {}

    #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU, priority: -10)]
    public function onAdminNavbarMenu(MenuEvent $event): void
    {
        $browsable = $this->registry->getBrowsable();
        if (empty($browsable)) {
            return;
        }

        $menu = $event->getMenu();

        foreach ($this->registry->getGroups() as $group) {
            $items = array_filter(
                $this->registry->getByGroup($group),
                fn ($d) => $d->adminBrowsable && $d->hasApiResource,
            );
            if (empty($items)) {
                continue;
            }

            $submenu = $this->addSubmenu($menu, $group);

            foreach ($items as $descriptor) {
                $badge = $this->resolveCount($descriptor->class);
                $this->add(
                    $submenu,
                    'survos_admin_browse',
                    ['class' => $descriptor->class],
                    $descriptor->label,
                    icon:  $descriptor->icon,
                    badge: $badge,
                );
            }
        }
    }

    private function resolveCount(string $class): ?string
    {
        if (!$this->managerRegistry) {
            return null;
        }
        try {
            $count = $this->managerRegistry->getManagerForClass($class)?->getRepository($class)->count([]);
            return $count !== null
                ? (new \NumberFormatter('en', \NumberFormatter::DECIMAL_COMPACT_SHORT))->format($count)
                : null;
        } catch (\Throwable) {
            return null;
        }
    }
}
