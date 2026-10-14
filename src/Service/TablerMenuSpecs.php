<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Survos\MenuBundle\MenuSlot;
use Survos\MenuBundle\Service\MenuSpecProviderInterface;

/**
 * How each page slot is drawn with survos/menu-bundle (survos_tabler.menu_renderer: survos): a slot theme
 * over the shared Tabler theme, over menu-bundle's base theme. The classes and flags come from
 * MenuSlotRegistry, so both renderers agree on them; only the templates differ.
 *
 * An app restyles a slot without copying anything: a template with just the blocks it changes, passed
 * per call ({{ tabler_menu(SIDEBAR, {}, {theme: '@App/menu/sidebar.html.twig'}) }}) or for every call via
 * survos_tabler.menu_themes.
 */
final class TablerMenuSpecs implements MenuSpecProviderInterface
{
    private const COMMON = '@SurvosTabler/menu_theme/common.html.twig';
    private const NAVBAR = '@SurvosTabler/menu_theme/navbar.html.twig';
    private const NAVBAR_END = '@SurvosTabler/menu_theme/navbar_end.html.twig';

    private const THEMES = [
        MenuSlot::BANNER => '@SurvosTabler/menu_theme/banner.html.twig',
        MenuSlot::BREADCRUMB => '@SurvosTabler/menu_theme/breadcrumb.html.twig',
        MenuSlot::PAGE_NAV => '@SurvosTabler/menu_theme/actions.html.twig',
        MenuSlot::PAGE_ACTIONS => '@SurvosTabler/menu_theme/actions.html.twig',
        MenuSlot::NAVBAR_PRIMARY => self::NAVBAR,
        MenuSlot::NAVBAR_LANGUAGE => self::NAVBAR_END,
        MenuSlot::NAVBAR_START => self::NAVBAR_END,
        MenuSlot::NAVBAR_END => self::NAVBAR_END,
        MenuSlot::NAVBAR_THEME => self::NAVBAR_END,
        MenuSlot::NAVBAR_NOTIFICATIONS => self::NAVBAR_END,
        MenuSlot::NAVBAR_APPS => self::NAVBAR_END,
        MenuSlot::AUTH => '@SurvosTabler/menu_theme/auth.html.twig',
        MenuSlot::SEARCH => '@SurvosTabler/menu_theme/search.html.twig',
        MenuSlot::NAVBAR_MENU => self::NAVBAR,
        MenuSlot::NAVBAR_MENU_END => self::NAVBAR,
        MenuSlot::ADMIN_NAVBAR_MENU => self::NAVBAR,
        MenuSlot::ADMIN_NAVBAR_MENU_END => self::NAVBAR,
        MenuSlot::SIDEBAR => '@SurvosTabler/menu_theme/sidebar.html.twig',
        MenuSlot::FOOTER => '@SurvosTabler/menu_theme/footer.html.twig',
        MenuSlot::FOOTER_END => '@SurvosTabler/menu_theme/footer.html.twig',
    ];

    /**
     * @param array<string, string> $appThemes slot => an app template stacked above the slot's theme
     */
    public function __construct(
        private readonly MenuSlotRegistry $slots,
        private readonly array $appThemes = [],
    ) {
    }

    public function menuSpec(string $name): array
    {
        $spec = $this->slots->spec($name);
        unset($spec['template'], $spec['area']);

        // MenuBuilderTrait marks every label safe; KnpMenu rendering honoured that, so this does too.
        $spec['allow_safe_labels'] = true;
        $spec['themes'] = array_values(array_filter([
            $this->appThemes[$name] ?? null,
            self::THEMES[$name] ?? self::NAVBAR,
            self::COMMON,
        ]));

        return $spec;
    }
}
