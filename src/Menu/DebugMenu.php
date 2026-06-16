<?php
/* src/Menu/DebugMenu.php v1.0 - Fills every menu slot with dummy data */

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Populates every menu slot with representative dummy items.
 *
 * Activates only when the `dummy_menu` menu option is set (see
 * {@see \Survos\TablerBundle\Controller\DebugController}). The option flows into
 * each {@see MenuEvent} via MenuOptionsResolver, so any page that sets it — not
 * just /debug-menu — gets the dummy data.
 */
final class DebugMenu implements EventSubscriberInterface
{
    use MenuBuilderTrait;

    public function __construct(
        protected readonly ?RouterInterface $router = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService $iconService = null,
    ) {}

    public static function getSubscribedEvents(): array
    {
        $events = [];
        foreach (MenuEvent::getConstants() as $slot) {
            // low priority so real menu items (if any) come first
            $events[$slot] = ['onMenuSlot', -1000];
        }

        return $events;
    }

    public function onMenuSlot(MenuEvent $event, string $slot): void
    {
        if (!$event->getOption('dummy_menu')) {
            return;
        }

        $this->populate($event, $slot);
    }

    private function populate(MenuEvent $event, string $slot): void
    {
        $menu = $event->getMenu();
        $label = $this->slotLabel($slot);

        // A heading, two plain items and a small submenu give a representative
        // sample of what a real slot can render.
        $this->addHeading($menu, $label);

        $this->add($menu, uri: '#', label: $label . ' One', icon: 'tabler:point', checkRouteExists: false);
        $this->add($menu, uri: '#', label: $label . ' Two', icon: 'tabler:point', checkRouteExists: false);

        $submenu = $this->addSubmenu($menu, $label . ' More', icon: 'tabler:dots');
        $this->add($submenu, uri: '#', label: 'Nested A', icon: 'tabler:chevron-right', checkRouteExists: false);
        $this->add($submenu, uri: '#', label: 'Nested B', icon: 'tabler:chevron-right', checkRouteExists: false);
    }

    private function slotLabel(string $slot): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $slot)));
    }
}
