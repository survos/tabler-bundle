<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

/**
 * Populates the navbar AUTH slot with Login/Register (anonymous) or Logout (authenticated).
 *
 * Tabler renders the AUTH slot but nothing filled it before, so every app had to add its own
 * listener (and most never did, leaving no login link). This makes it automatic for any app
 * that exposes login/logout routes: by default app_login / app_logout / app_register
 * (configurable via survos_tabler.routes), with a fallback to auth-bundle's auth_* routes.
 *
 * Each link self-removes when its route is absent (add() checks route existence), so this is
 * safe on sites with no authentication at all — the slot simply stays empty.
 */
final class AuthSlotMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        protected readonly ?RouterInterface   $router            = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService       $iconService       = null,
        protected readonly ?Security          $security          = null,
    ) {}

    #[AsEventListener(event: MenuEvent::AUTH)]
    public function onAuthMenu(MenuEvent $event): void
    {
        $menu = $event->getMenu();

        if ($this->security?->getUser()) {
            if ($logout = $this->route('logout', ['app_logout', 'auth_logout', 'logout'])) {
                $this->add($menu, $logout, label: 'Logout', icon: 'logout');
            }

            return;
        }

        if ($login = $this->route('login', ['app_login', 'auth_login', 'login'])) {
            $this->add($menu, $login, label: 'Login', icon: 'login');
        }
        if ($register = $this->route('register', ['app_register', 'auth_register', 'register'])) {
            $this->add($menu, $register, label: 'Register', icon: 'user-plus');
        }
    }

    /**
     * Resolve a route: prefer the configured survos_tabler.routes alias, then fall back to
     * conventional route names. Returns null when none of them exist.
     */
    private function route(string $alias, array $fallbacks): ?string
    {
        if ($routeName = $this->routeAliasService?->get($alias)) {
            return $routeName;
        }
        foreach ($fallbacks as $candidate) {
            if ($this->routeExists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
