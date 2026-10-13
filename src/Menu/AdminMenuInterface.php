<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Knp\Menu\ItemInterface;

interface AdminMenuInterface
{
    public function addMenuItem(ItemInterface $menu, $options): ItemInterface;
}
