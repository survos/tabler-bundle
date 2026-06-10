<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

/**
 * Adds a link to the zenstruck/messenger-monitor dashboard when that bundle is
 * installed.
 *
 * messenger-monitor is a third-party bundle, NOT a survos dependency, so tabler
 * cannot reference its classes. Instead we detect it at runtime: if its dashboard
 * route is registered, we add the link; otherwise the item is silently skipped.
 * (add() already enforces this via checkRouteExists, so the link self-removes the
 * moment the monitor bundle is uninstalled — no hard failure.)
 */
final class MessengerMonitorMenuSubscriber
{
    use MenuBuilderTrait;

    /** Dashboard route exposed by zenstruck/messenger-monitor-bundle. */
    private const MONITOR_ROUTE = 'zenstruck_messenger_monitor_dashboard';

    public function __construct(
        protected readonly ?RouterInterface   $router            = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService       $iconService       = null,
    ) {}

    #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU)]
    public function onAdminNavbarMenu(MenuEvent $event): void
    {
        $this->add(
            $event->getMenu(),
            self::MONITOR_ROUTE,
            label: 'Messenger',
            icon: 'activity',
        );
    }
}
