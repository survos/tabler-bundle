<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use PHPUnit\Framework\TestCase;
use Survos\MenuBundle\MenuSlot;
use Survos\TablerBundle\Service\MenuSlotRegistry;
use Survos\TablerBundle\Service\TablerMenuSpecs;

final class TablerMenuSpecsTest extends TestCase
{
    public function testEverySlotHasAThemeStackOverTheCommonTheme(): void
    {
        $specs = new TablerMenuSpecs(new MenuSlotRegistry());

        foreach (MenuSlot::all() as $slot) {
            $spec = $specs->menuSpec($slot);
            self::assertSame('@SurvosTabler/menu_theme/common.html.twig', end($spec['themes']), $slot);
            self::assertArrayNotHasKey('template', $spec, 'the knp template is not a theme');
            self::assertArrayNotHasKey('area', $spec);
            self::assertFileExists(dirname(__DIR__, 2).'/templates/menu_theme/'.basename($spec['themes'][0]), $slot);
        }
    }

    public function testSlotClassesComeFromTheRegistryAndAppThemesStackOnTop(): void
    {
        $specs = new TablerMenuSpecs(new MenuSlotRegistry(), [MenuSlot::SIDEBAR => 'menu/sidebar.html.twig']);

        $sidebar = $specs->menuSpec(MenuSlot::SIDEBAR);

        self::assertSame('menu/sidebar.html.twig', $sidebar['themes'][0]);
        self::assertSame(['class' => 'navbar-nav pt-lg-3'], $sidebar['rootAttributes']);
        self::assertSame('@SurvosTabler/menu_theme/navbar.html.twig', $specs->menuSpec('card.actions')['themes'][0], 'custom menus draw like the navbar');
    }

    public function testDeprecatedConstantsAreTheMenuBundleSlots(): void
    {
        self::assertSame(MenuSlot::NAVBAR_END, \Survos\TablerBundle\Event\MenuEvent::NAVBAR_END);
        self::assertSame(MenuSlot::all(), \Survos\TablerBundle\Event\MenuEvent::getConstants());
    }
}
