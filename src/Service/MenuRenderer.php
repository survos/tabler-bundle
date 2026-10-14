<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Knp\Menu\ItemInterface;
use Knp\Menu\Twig\Helper;
use Survos\MenuBundle\Service\Menus;

/**
 * The one way to render a menu: tabler_menu('NAVBAR_END') in Twig, or MenuRenderer::render() in PHP.
 *
 * The name is the event name. Listeners populate it (#[AsMenuListener(MenuSlot::AUTH)]); this class
 * dispatches it, then renders what the listeners built.
 *
 * Two renderers, chosen by survos_tabler.menu_renderer:
 *  - knp (default): KnpMenu's Twig renderer with the @SurvosTabler/menu/*.html.twig templates (MenuSlotRegistry);
 *  - survos: survos/menu-bundle's themes (@SurvosTabler/menu_theme/*, TablerMenuSpecs). Same listeners,
 *    same menus; the markup comes from composable themes and a pre-resolved item view.
 */
class MenuRenderer
{
    public function __construct(
        private readonly MenuDispatcher $dispatcher,
        private readonly Helper $knpHelper,
        private readonly MenuSlotRegistry $slots,
        private readonly ?Menus $menus = null,
    ) {
    }

    /**
     * @param array<string, mixed> $options listener context: what the event's getOption() returns
     * @param array<string, mixed> $render  render overrides for this call (template/theme, rootAttributes, ...)
     */
    public function render(string $name, array $options = [], array $render = []): string
    {
        if (null !== $this->menus) {
            return $this->menus->render($name, $options, $render);
        }

        $menu = $this->dispatcher->dispatch($name, $options);
        if (!$menu->hasChildren()) {
            return '';
        }

        $spec = $this->slots->spec($name);
        unset($spec['area']);

        return $this->knpHelper->render($menu, array_replace(['allow_safe_labels' => true], $spec, $render));
    }

    public function getMenu(string $name, array $options = []): ItemInterface
    {
        return $this->dispatcher->dispatch($name, $options);
    }

    public function hasItems(string $name, array $options = []): bool
    {
        if (null !== $this->menus) {
            return $this->menus->hasItems($name, $options);
        }

        return $this->dispatcher->dispatch($name, $options)->hasChildren();
    }

    public function usesMenuBundle(): bool
    {
        return null !== $this->menus;
    }
}
