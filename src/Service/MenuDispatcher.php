<?php
/* src/Service/MenuDispatcher.php v2.1 - Root item not displayed */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Event\MenuEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MenuDispatcher
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly EventDispatcherInterface $dispatcher,
    ) {}

    public function dispatch(string $slot, array $options = []): ItemInterface
    {
        // Create root menu - name doesn't matter since it won't be displayed
        $menu = $this->factory->createItem($options['name'] ?? $slot);

        // IMPORTANT: Don't display the root item itself, only its children
        $menu->setDisplay(false);

        $event = new MenuEvent($menu, $this->factory, $options);
        $this->dispatcher->dispatch($event, $slot);

        return $menu;
    }

    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }
}
