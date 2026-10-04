<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Survos\TablerBundle\Event\MenuEvent;

/**
 * How each registered slot is rendered: the KnpMenu template plus the options that used to live in a
 * Twig switch (components/menu.html.twig). One place, so tabler_menu(), the debug view and the base
 * template all agree. Any name that is not registered is a custom (element-scoped) menu and gets the
 * default spec; see MenuRenderer.
 */
final class MenuSlotRegistry
{
    private const NAVBAR = '@SurvosTabler/menu/navbar.html.twig';
    private const NAVBAR_END = '@SurvosTabler/menu/navbar_end.html.twig';

    /** Rendering defaults for a name no slot owns (a menu attached to an element, not to the page shell). */
    private const DEFAULT = ['template' => self::NAVBAR, 'area' => 'custom'];

    /** @var array<string, array<string, mixed>> */
    private const SLOTS = [
        MenuEvent::BANNER => ['area' => 'banner', 'template' => '@SurvosTabler/menu/banner.html.twig'],
        MenuEvent::BREADCRUMB => ['area' => 'page-header', 'template' => '@SurvosTabler/menu/breadcrumb.html.twig'],
        MenuEvent::PAGE_NAV => ['area' => 'page-header', 'template' => '@SurvosTabler/menu/actions.html.twig'],
        MenuEvent::PAGE_ACTIONS => ['area' => 'page-header', 'template' => '@SurvosTabler/menu/actions.html.twig'],

        MenuEvent::NAVBAR_PRIMARY => ['area' => 'navbar', 'template' => self::NAVBAR, 'currentClass' => 'active', 'rootAttributes' => ['class' => 'navbar-nav flex-row']],
        // a tool like the theme picker, not navigation: same compact toggle, and no 'active' underline just because
        // the current locale's link matches the page URL
        MenuEvent::NAVBAR_LANGUAGE => ['area' => 'navbar', 'template' => self::NAVBAR_END, 'rootAttributes' => ['class' => 'd-flex']],
        MenuEvent::NAVBAR_START => ['area' => 'navbar', 'template' => self::NAVBAR_END, 'rootAttributes' => ['class' => 'd-flex']],
        MenuEvent::NAVBAR_END => ['area' => 'navbar', 'template' => self::NAVBAR_END, 'rootAttributes' => ['class' => 'd-flex']],
        MenuEvent::NAVBAR_THEME => ['area' => 'navbar', 'template' => self::NAVBAR_END, 'rootAttributes' => ['class' => 'd-flex']],
        MenuEvent::NAVBAR_NOTIFICATIONS => ['area' => 'navbar', 'template' => self::NAVBAR_END, 'rootAttributes' => ['class' => 'd-flex']],
        MenuEvent::NAVBAR_APPS => ['area' => 'navbar', 'template' => self::NAVBAR_END, 'rootAttributes' => ['class' => 'd-flex']],
        MenuEvent::AUTH => ['area' => 'navbar', 'template' => '@SurvosTabler/menu/auth.html.twig'],
        MenuEvent::SEARCH => ['area' => 'navbar', 'template' => '@SurvosTabler/menu/search.html.twig'],

        MenuEvent::NAVBAR_MENU => ['area' => 'navbar-secondary', 'template' => self::NAVBAR, 'currentClass' => 'active', 'dense' => true, 'rootAttributes' => ['class' => 'navbar-nav']],
        MenuEvent::NAVBAR_MENU_END => ['area' => 'navbar-secondary', 'template' => self::NAVBAR, 'currentClass' => 'active', 'dense' => true, 'rootAttributes' => ['class' => 'navbar-nav']],

        MenuEvent::ADMIN_NAVBAR_MENU => ['area' => 'admin', 'template' => self::NAVBAR, 'currentClass' => 'active', 'compact' => true, 'rootAttributes' => ['class' => 'navbar-nav flex-row flex-wrap']],
        MenuEvent::ADMIN_NAVBAR_MENU_END => ['area' => 'admin', 'template' => self::NAVBAR, 'currentClass' => 'active', 'compact' => true, 'rootAttributes' => ['class' => 'navbar-nav flex-row flex-wrap']],

        MenuEvent::SIDEBAR => ['area' => 'sidebar', 'template' => '@SurvosTabler/menu/sidebar.html.twig', 'currentClass' => 'active', 'ancestorClass' => 'active', 'rootAttributes' => ['class' => 'navbar-nav pt-lg-3']],

        MenuEvent::FOOTER => ['area' => 'footer', 'template' => '@SurvosTabler/menu/footer.html.twig', 'rootAttributes' => ['class' => 'list-inline list-inline-dots mb-0']],
        MenuEvent::FOOTER_END => ['area' => 'footer', 'template' => '@SurvosTabler/menu/footer.html.twig', 'rootAttributes' => ['class' => 'list-inline list-inline-dots mb-0']],
    ];

    public function has(string $name): bool
    {
        return isset(self::SLOTS[$name]);
    }

    /** @return array<string, mixed> KnpMenu render options, plus `area` (not passed to KnpMenu) */
    public function spec(string $name): array
    {
        return self::SLOTS[$name] ?? self::DEFAULT;
    }

    /** @return list<string> */
    public function names(): array
    {
        return array_keys(self::SLOTS);
    }
}
