<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\TestCase;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\MenuContext;
use Survos\TablerBundle\Service\MenuDispatcher;
use Survos\TablerBundle\Service\MenuOptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class MenuDispatcherMemoTest extends TestCase
{
    public function testListenersRunOncePerSlotAndOptionsPerRequest(): void
    {
        $calls = 0;
        $events = new EventDispatcher();
        $events->addListener('NAVBAR_MENU', static function (MenuEvent $e) use (&$calls): void {
            ++$calls;
            $e->getMenu()->addChild('x');
        });
        $stack = new RequestStack();
        $stack->push(new Request());
        $dispatcher = new MenuDispatcher(new MenuFactory(), $events, new MenuOptionsResolver([], new MenuContext($stack)), $stack);

        $paper = new \stdClass();
        $first = $dispatcher->dispatch('NAVBAR_MENU', ['paper' => $paper]);
        self::assertSame($first, $dispatcher->dispatch('NAVBAR_MENU', ['paper' => $paper]), 'has-items then render is one build');
        self::assertSame(1, $calls);

        $dispatcher->dispatch('NAVBAR_MENU', ['paper' => new \stdClass()]);
        self::assertSame(2, $calls, 'a different entity is a different menu');

        $stack->push(new Request());
        $dispatcher->dispatch('NAVBAR_MENU', ['paper' => $paper]);
        self::assertSame(3, $calls, 'the next request never sees this request\'s menu');
    }
}
