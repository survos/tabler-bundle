<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Knp\Menu\ItemInterface;
use Survos\MenuBundle\Event\MenuEvent as BaseMenuEvent;
use Survos\MenuBundle\Service\MenuDispatcher as BaseMenuDispatcher;
use Survos\TablerBundle\Event\MenuEvent;

/**
 * survos/menu-bundle's dispatcher, dispatching tabler's MenuEvent subclass so listeners type-hinted on
 * Survos\TablerBundle\Event\MenuEvent keep working. It also stands in for the menu-bundle service (see
 * SurvosTablerBundle::process()), so <twig:menu> and menu() build exactly the menus tabler_menu() does.
 */
class MenuDispatcher extends BaseMenuDispatcher
{
    protected function createEvent(ItemInterface $menu, array $options): BaseMenuEvent
    {
        return new MenuEvent($menu, $this->getFactory(), $options);
    }
}
