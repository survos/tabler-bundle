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

        // Heading + a couple of plain links: the simplest slot content.
        $this->addHeading($menu, $label);
        $this->add($menu, uri: '#', label: $label . ' One', icon: 'tabler:point', checkRouteExists: false);
        $this->add($menu, uri: '#', label: $label . ' Two', icon: 'tabler:point', checkRouteExists: false, badge: '3');

        // A dropdown group built the way real app menus build them (addSubmenu +
        // add() children), with a divider, a badge and a nested sub-dropdown so
        // the debug page exercises the full dropdown render path — not just flat
        // links.
        $group = $this->addSubmenu($menu, $label . ' Group', icon: 'tabler:layout-grid');
        $this->add($group, uri: '#', label: 'Action', icon: 'tabler:bolt', checkRouteExists: false);
        $this->add($group, uri: '#', label: 'Another action', icon: 'tabler:bolt', checkRouteExists: false, badge: 'new');
        $this->add($group, uri: '#', label: 'Separated', icon: 'tabler:flag', checkRouteExists: false, dividerBefore: true);

        $nested = $this->addSubmenu($group, 'Nested group', icon: 'tabler:folder');
        $this->add($nested, uri: '#', label: 'Nested A', icon: 'tabler:chevron-right', checkRouteExists: false);
        $this->add($nested, uri: '#', label: 'Nested B', icon: 'tabler:chevron-right', checkRouteExists: false);

        // A second, smaller dropdown.
        $actions = $this->addSubmenu($menu, $label . ' Actions', icon: 'tabler:dots');
        $this->add($actions, uri: '#', label: 'Do thing', icon: 'tabler:check', checkRouteExists: false);

        // Empty-submenu demo: every child targets a missing route, so add()
        // filters them all out and this submenu ends up with zero children. The
        // navbar renderer must drop it entirely — nothing should render here.
        // (Same shape an admin menu takes when IsGranted hides every child.)
        $gated = $this->addSubmenu($menu, $label . ' Gated', icon: 'tabler:lock');
        $this->add($gated, route: 'debug_menu_missing_route_' . strtolower($slot), label: 'Hidden item');
    }

    private function slotLabel(string $slot): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $slot)));
    }
}
