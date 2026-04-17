<?php

declare(strict_types=1);

namespace Survos\TablerBundle\EventSubscriber;

use Survos\TablerBundle\Event\MenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class DebugMenuSlotsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {}

    public static function getSubscribedEvents(): array
    {
        $events = [];
        foreach (MenuEvent::getConstants() as $slot) {
            $events[$slot] = ['onMenuSlot', 1000];
        }

        return $events;
    }

    public function onMenuSlot(MenuEvent $event, string $slot): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        $menu = $event->getMenu();
        $id = strtolower($slot) . '_debug_slot';
        if ($menu->getChild($id) !== null) {
            return;
        }

        $child = $menu->addChild($id, [
            'uri' => '#debug-menu-slot-' . strtolower($slot),
            'label' => 'This is ' . $slot,
        ]);
        $child->setExtra('translation_domain', false);
        $child->setExtra('tooltip', 'Debug slot placeholder');
        $child->setExtra('icon', $this->iconForSlot($slot));
        $child->setExtra('debug_menu_slot', true);
    }

    private function isEnabled(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return false;
        }

        return $request->query->getBoolean('debugMenuSlots');
    }

    private function iconForSlot(string $slot): string
    {
        return match ($slot) {
            MenuEvent::NAVBAR_MENU,
            MenuEvent::NAVBAR_MENU_END => 'tabler:menu-2',
            default => 'menu-2',
        };
    }
}
