<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use PHPUnit\Framework\TestCase;
use Survos\TablerBundle\Components\MenuComponent;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\MenuSlotRegistry;

final class MenuSlotRegistryTest extends TestCase
{
    public function testEveryDeclaredSlotIsRegistered(): void
    {
        $registry = new MenuSlotRegistry();
        foreach (MenuEvent::getConstants() as $slot) {
            self::assertTrue($registry->has($slot), "$slot has no render spec in MenuSlotRegistry");
        }
    }

    public function testUnknownNamesGetTheDefaultSpec(): void
    {
        $registry = new MenuSlotRegistry();
        self::assertFalse($registry->has('card.actions'));
        self::assertSame('custom', $registry->spec('card.actions')['area']);
    }

    public function testComponentRefusesRegisteredSlots(): void
    {
        $component = new MenuComponent(new MenuSlotRegistry());
        $component->name = MenuEvent::NAVBAR_END;

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('tabler_menu(NAVBAR_END)');
        $component->validate();
    }

    public function testComponentRefusesTheOldTypeProp(): void
    {
        $component = new MenuComponent(new MenuSlotRegistry());
        $component->type = MenuEvent::SIDEBAR;

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('"type" prop');
        $component->validate();
    }

    public function testComponentAcceptsACustomName(): void
    {
        $component = new MenuComponent(new MenuSlotRegistry());
        $component->name = 'card.actions';
        $component->validate();

        self::assertSame('card.actions', $component->name);
    }
}
