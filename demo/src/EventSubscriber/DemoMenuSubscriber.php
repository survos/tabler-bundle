<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Event\MenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsEventListener(event: MenuEvent::NAVBAR_END, method: 'onNavbarEnd')]
#[AsEventListener(event: MenuEvent::NAVBAR_THEME, method: 'onNavbarTheme')]
#[AsEventListener(event: MenuEvent::NAVBAR_NOTIFICATIONS, method: 'onNavbarNotifications')]
#[AsEventListener(event: MenuEvent::NAVBAR_APPS, method: 'onNavbarApps')]
#[AsEventListener(event: MenuEvent::NAVBAR_LANGUAGE, method: 'onNavbarLanguage')]
#[AsEventListener(event: MenuEvent::AUTH, method: 'onAuth')]
#[AsEventListener(event: MenuEvent::SEARCH, method: 'onSearch')]
#[AsEventListener(event: MenuEvent::NAVBAR_MENU, method: 'onNavbarMenu')]
#[AsEventListener(event: MenuEvent::NAVBAR_MENU_END, method: 'onNavbarMenuEnd')]
#[AsEventListener(event: MenuEvent::SIDEBAR, method: 'onSidebar')]
#[AsEventListener(event: MenuEvent::BREADCRUMB, method: 'onBreadcrumb')]
#[AsEventListener(event: MenuEvent::PAGE_ACTIONS, method: 'onPageActions')]
#[AsEventListener(event: MenuEvent::FOOTER, method: 'onFooter')]
#[AsEventListener(event: MenuEvent::FOOTER_END, method: 'onFooterEnd')]
final class DemoMenuSubscriber
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {}

    public function onNavbarTheme(MenuEvent $event): void
    {
        $this->link($event->menu, 'Theme', '#theme', 'moon');
    }

    public function onNavbarNotifications(MenuEvent $event): void
    {
        $this->link($event->menu, 'Alerts', '#alerts', 'bell');
    }

    public function onNavbarApps(MenuEvent $event): void
    {
        $this->link($event->menu, 'Apps', '#apps', 'layout-grid-add');
    }

    public function onNavbarLanguage(MenuEvent $event): void
    {
        $this->link($event->menu, 'EN', '#language', 'language');
    }

    public function onNavbarEnd(MenuEvent $event): void
    {
        $this->link($event->menu, 'Source', 'https://github.com/survos-sites/tabler-bundle', 'brand-github', true);
        $this->link($event->menu, 'Sponsor', 'https://github.com/sponsors', 'heart', true);
    }

    public function onAuth(MenuEvent $event): void
    {
        $this->link($event->menu, 'Profile', '/profile', 'user');
        $this->link($event->menu, 'Login', '/login', 'login');
    }

    public function onSearch(MenuEvent $event): void
    {
        $this->link($event->menu, 'Search', '/search', 'search');
    }

    public function onNavbarMenu(MenuEvent $event): void
    {
        $menu = $event->menu;

        $this->link($menu, 'Home', '/', 'tabler:home');

        $admin = $menu->addChild('admin', ['label' => 'Admin', 'uri' => '/admin']);
        $admin->setExtra('icon', 'tabler:settings');
        $this->link($admin, 'Tenants', '/tenants');
        $this->link($admin, 'Museums', '/museums');

        $collections = $menu->addChild('collections', ['label' => 'Collections', 'uri' => '/museums']);
        $collections->setExtra('icon', 'tabler:building-museum');
        $this->link($collections, 'Epochs', '/museums/demo/epochs');
        $this->link($collections, 'Search', '/search');
    }

    public function onNavbarMenuEnd(MenuEvent $event): void
    {
        $this->link($event->menu, 'Theme Settings', '#settings', 'tabler:settings');
    }

    public function onSidebar(MenuEvent $event): void
    {
        $route = $this->route();
        $menu = $event->menu;

        if (str_starts_with($route, 'app_tenant_')) {
            $this->link($menu, 'Tenant overview', '/tenants/dev');
            $this->link($menu, 'Intakes', '/tenants/dev/intakes');
            $this->link($menu, 'Images', '/tenants/dev/images');
            $this->link($menu, 'Members', '/tenants/dev');
            return;
        }

        if (str_starts_with($route, 'app_museum_')) {
            $this->link($menu, 'Museums', '/museums');
            $this->link($menu, 'Epochs', '/museums/demo/epochs');
            $this->link($menu, 'Search', '/search');
            return;
        }

        $this->link($menu, 'Overview', '/');
        $this->link($menu, 'Admin', '/admin');
        $this->link($menu, 'Search', '/search');
    }

    public function onBreadcrumb(MenuEvent $event): void
    {
        $route = $this->route();
        $menu = $event->menu;

        $this->link($menu, 'Demo', '/');

        $segments = match ($route) {
            'app_admin' => [['Admin', '/admin']],
            'app_tenants' => [['Admin', '/admin'], ['Tenants', '/tenants']],
            'app_tenant_show' => [['Admin', '/admin'], ['Tenants', '/tenants'], ['Tenant', '/tenants/dev']],
            'app_tenant_intakes' => [['Admin', '/admin'], ['Tenants', '/tenants'], ['Tenant', '/tenants/dev'], ['Intakes', '/tenants/dev/intakes']],
            'app_tenant_images' => [['Admin', '/admin'], ['Tenants', '/tenants'], ['Tenant', '/tenants/dev'], ['Images', '/tenants/dev/images']],
            'app_museums' => [['Collection', '/museums'], ['Museums', '/museums']],
            'app_museum_epochs' => [['Collection', '/museums'], ['Museums', '/museums'], ['Epochs', '/museums/demo/epochs']],
            'app_search' => [['Tools', '/search'], ['Search', '/search']],
            'app_profile' => [['Auth', '/profile'], ['Profile', '/profile']],
            'app_login' => [['Auth', '/login'], ['Login', '/login']],
            default => [],
        };

        foreach ($segments as [$label, $uri]) {
            $this->link($menu, $label, $uri);
        }
    }

    public function onPageActions(MenuEvent $event): void
    {
        $route = $this->route();
        $menu = $event->menu;

        if (in_array($route, ['app_tenants', 'app_tenant_show', 'app_tenant_intakes', 'app_tenant_images'], true)) {
            $this->link($menu, 'Tenant dashboard', '/tenants/dev');
            $this->link($menu, 'Intakes', '/tenants/dev/intakes');
            $this->link($menu, 'Images', '/tenants/dev/images');
            $this->link($menu, 'Members', '/tenants/dev');
            return;
        }

        if (in_array($route, ['app_museums', 'app_museum_epochs'], true)) {
            $this->link($menu, 'List', '/museums');
            $this->link($menu, 'Epochs', '/museums/demo/epochs');
            $this->link($menu, 'Edit', '/museums/demo/epochs');
            return;
        }

        $this->link($menu, 'New', '#new');
        $this->link($menu, 'Export', '#export');
    }

    public function onFooter(MenuEvent $event): void
    {
        $this->link($event->menu, 'Documentation', 'https://tabler.io/', 'file-text', true);
        $this->link($event->menu, 'Repository', 'https://github.com/survos-sites/tabler-bundle', 'brand-github', true);
    }

    public function onFooterEnd(MenuEvent $event): void
    {
        $this->link($event->menu, 'Demo app', '/', 'external-link');
    }

    private function link(ItemInterface $menu, string $label, string $uri, ?string $icon = null, bool $external = false): void
    {
        $child = $menu->addChild(strtolower(str_replace(' ', '_', $label)).'_'.substr(md5($uri), 0, 6), [
            'label' => $label,
            'uri' => $uri,
        ]);

        if ($icon !== null) {
            $child->setExtra('icon', $icon);
        }

        $child->setExtra('translation_domain', false);

        if ($external) {
            $child->setLinkAttribute('target', '_blank');
        }
    }

    private function route(): string
    {
        return (string) $this->requestStack->getCurrentRequest()?->attributes->get('_route', 'app_homepage');
    }
}
