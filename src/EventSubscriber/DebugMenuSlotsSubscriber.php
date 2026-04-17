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
        private readonly bool $enabledByConfig = false,
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
    }

    private function isEnabled(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return $this->enabledByConfig;
        }

        return $this->enabledByConfig || $request->query->getBoolean('debugMenuSlots');
    }

}
