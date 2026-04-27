<?php
/* src/Event/MenuEvent.php v2.1 - Menu slots */

declare(strict_types=1);

namespace Survos\TablerBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

class MenuEvent extends Event
{
    // Global / page-level slots
    public const BANNER = 'BANNER';
    public const BREADCRUMB = 'BREADCRUMB';
    public const PAGE_NAV = 'PAGE_NAV';
    public const PAGE_ACTIONS = 'PAGE_ACTIONS';

    // Top navbar (first header) - split so apps can hook in cleanly
    public const NAVBAR_START = 'NAVBAR_START';           // between brand and right side (rare)
    public const NAVBAR_END = 'NAVBAR_END';               // right side "tools" (links/buttons/etc.)
    public const NAVBAR_THEME = 'NAVBAR_THEME';           // theme toggle(s)
    public const NAVBAR_NOTIFICATIONS = 'NAVBAR_NOTIFICATIONS';
    public const NAVBAR_APPS = 'NAVBAR_APPS';
    public const NAVBAR_LANGUAGE = 'NAVBAR_LANGUAGE';     // locale/language selector (if you want it as a menu slot)
    public const AUTH = 'AUTH';                           // user dropdown (kept for BC)
    public const SEARCH = 'SEARCH';                       // search widget (kept for BC)

    // Secondary navbar (second header)
    public const NAVBAR_MENU = 'NAVBAR_MENU';
    public const NAVBAR_MENU_END = 'NAVBAR_MENU_END';

    // Admin navbar (third header) — only rendered for ROLE_ADMIN or app.debug
    public const ADMIN_NAVBAR_MENU = 'ADMIN_NAVBAR_MENU';
    public const ADMIN_NAVBAR_MENU_END = 'ADMIN_NAVBAR_MENU_END';

    // Sidebar
    public const SIDEBAR = 'SIDEBAR';

    // Footer
    public const FOOTER = 'FOOTER';
    public const FOOTER_END = 'FOOTER_END';

    public function __construct(
        public readonly ItemInterface $menu,
        public readonly FactoryInterface $factory,
        public readonly array $options = [],
        public readonly array $childOptions = [],
    ) {}

    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }

    public function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * Get all menu slot constants as name => value pairs
     */
    public static function getConstants(): array
    {
        $reflection = new \ReflectionClass(__CLASS__);
        $constants = [];
        foreach ($reflection->getConstants() as $name => $value) {
            if ($name === $value && preg_match('/^[A-Z_]+$/', $name)) {
                $constants[$name] = $value;
            }
        }
        return $constants;
    }
}
