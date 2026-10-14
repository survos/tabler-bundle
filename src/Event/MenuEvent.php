<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Event;

use Survos\MenuBundle\Event\MenuEvent as BaseMenuEvent;
use Survos\MenuBundle\MenuSlot;

/**
 * The event tabler's menus are dispatched with. It is survos/menu-bundle's MenuEvent; this subclass remains so
 * listeners type-hinted on it, and code reading its slot constants, keep working.
 *
 * New code: type-hint {@see BaseMenuEvent} and use {@see MenuSlot} for slot names
 * (#[AsMenuListener(MenuSlot::NAVBAR_END)]). The constants below are kept for BC and will be removed.
 */
class MenuEvent extends BaseMenuEvent
{
    /** @deprecated use {@see MenuSlot::BANNER} */
    public const BANNER = MenuSlot::BANNER;
    /** @deprecated use {@see MenuSlot::BREADCRUMB} */
    public const BREADCRUMB = MenuSlot::BREADCRUMB;
    /** @deprecated use {@see MenuSlot::PAGE_NAV} */
    public const PAGE_NAV = MenuSlot::PAGE_NAV;
    /** @deprecated use {@see MenuSlot::PAGE_ACTIONS} */
    public const PAGE_ACTIONS = MenuSlot::PAGE_ACTIONS;
    /** @deprecated use {@see MenuSlot::NAVBAR_PRIMARY} */
    public const NAVBAR_PRIMARY = MenuSlot::NAVBAR_PRIMARY;
    /** @deprecated use {@see MenuSlot::NAVBAR_START} */
    public const NAVBAR_START = MenuSlot::NAVBAR_START;
    /** @deprecated use {@see MenuSlot::NAVBAR_END} */
    public const NAVBAR_END = MenuSlot::NAVBAR_END;
    /** @deprecated use {@see MenuSlot::NAVBAR_THEME} */
    public const NAVBAR_THEME = MenuSlot::NAVBAR_THEME;
    /** @deprecated use {@see MenuSlot::NAVBAR_NOTIFICATIONS} */
    public const NAVBAR_NOTIFICATIONS = MenuSlot::NAVBAR_NOTIFICATIONS;
    /** @deprecated use {@see MenuSlot::NAVBAR_APPS} */
    public const NAVBAR_APPS = MenuSlot::NAVBAR_APPS;
    /** @deprecated use {@see MenuSlot::NAVBAR_LANGUAGE} */
    public const NAVBAR_LANGUAGE = MenuSlot::NAVBAR_LANGUAGE;
    /** @deprecated use {@see MenuSlot::AUTH} */
    public const AUTH = MenuSlot::AUTH;
    /** @deprecated use {@see MenuSlot::SEARCH} */
    public const SEARCH = MenuSlot::SEARCH;
    /** @deprecated use {@see MenuSlot::NAVBAR_MENU} */
    public const NAVBAR_MENU = MenuSlot::NAVBAR_MENU;
    /** @deprecated use {@see MenuSlot::NAVBAR_MENU_END} */
    public const NAVBAR_MENU_END = MenuSlot::NAVBAR_MENU_END;
    /** @deprecated use {@see MenuSlot::ADMIN_NAVBAR_MENU} */
    public const ADMIN_NAVBAR_MENU = MenuSlot::ADMIN_NAVBAR_MENU;
    /** @deprecated use {@see MenuSlot::ADMIN_NAVBAR_MENU_END} */
    public const ADMIN_NAVBAR_MENU_END = MenuSlot::ADMIN_NAVBAR_MENU_END;
    /** @deprecated use {@see MenuSlot::SIDEBAR} */
    public const SIDEBAR = MenuSlot::SIDEBAR;
    /** @deprecated use {@see MenuSlot::FOOTER} */
    public const FOOTER = MenuSlot::FOOTER;
    /** @deprecated use {@see MenuSlot::FOOTER_END} */
    public const FOOTER_END = MenuSlot::FOOTER_END;

    /**
     * Slot constants as name => value pairs.
     *
     * @deprecated use {@see MenuSlot::all()}
     *
     * @return array<string, string>
     */
    public static function getConstants(): array
    {
        return MenuSlot::all();
    }
}
