<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Knp\Menu\ItemInterface;
use Knp\Menu\Twig\Helper;

/**
 * The one way to render a menu: tabler_menu('NAVBAR_END') in Twig, or MenuRenderer::render() in PHP.
 *
 * The name is the event name. Listeners populate it (#[AsEventListener(event: MenuEvent::AUTH)]); this
 * class dispatches it, then renders what the listeners built. A registered slot gets its template and
 * options from MenuSlotRegistry; any other name is a custom, element-scoped menu with default rendering.
 */
class MenuRenderer
{
    public function __construct(
        private readonly MenuDispatcher $dispatcher,
        private readonly Helper $knpHelper,
        private readonly MenuSlotRegistry $slots,
    ) {}

    /**
     * @param array<string, mixed> $options listener context: what the event's getOption() returns
     * @param array<string, mixed> $render  KnpMenu render overrides for this call (template, rootAttributes, ...)
     */
    public function render(string $name, array $options = [], array $render = []): string
    {
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
        return $this->dispatcher->dispatch($name, $options)->hasChildren();
    }
}
