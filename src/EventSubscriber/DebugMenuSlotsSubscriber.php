<?php

declare(strict_types=1);

namespace Survos\TablerBundle\EventSubscriber;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Menu\MenuBuilderTrait;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

final class DebugMenuSlotsSubscriber implements EventSubscriberInterface
{
    use MenuBuilderTrait;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly bool $enabledByConfig = false,
        protected readonly ?RouterInterface $router = null,
        protected readonly ?RouteAliasService $routeAliasService = null,
        protected readonly ?IconService $iconService = null,
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
        if ($slot === MenuEvent::ADMIN_NAVBAR_MENU) {
            $this->addDebugToggle($event);
        }

        if (!$this->isEnabled()) {
            return;
        }
    }

    private function addDebugToggle(MenuEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $isActive = $this->isEnabled();
        $params = $request->query->all();

        if ($isActive) {
            unset($params['debugMenuSlots']);
        } else {
            $params['debugMenuSlots'] = 1;
        }

        $uri = $request->getPathInfo() . ($params ? '?' . http_build_query($params) : '');

        $this->add(
            $event->menu,
            uri: $uri,
            label: $isActive ? 'Slots ✓' : 'Slots',
            icon: 'tabler:layout-grid',
        );
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
