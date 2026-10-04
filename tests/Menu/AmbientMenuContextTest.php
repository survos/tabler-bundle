<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use PHPUnit\Framework\TestCase;
use Survos\TablerBundle\Menu\AmbientMenuContext;
use Survos\TablerBundle\Service\MenuContext;
use Survos\TablerBundle\Service\MenuOptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class AmbientMenuContextTest extends TestCase
{
    public function testControllerObjectsReachListenersButStringsAndExplicitOptionsDoNot(): void
    {
        $stack = new RequestStack();
        $request = new Request();
        $stack->push($request);
        $context = new MenuContext($stack);

        $entity = new \stdClass();
        $kernel = $this->createStub(HttpKernelInterface::class);
        $event = new ControllerArgumentsEvent(
            $kernel,
            static fn (\stdClass $publication, string $issue, Request $request) => null,
            [$entity, 'code-123', $request],
            $request,
            HttpKernelInterface::MAIN_REQUEST,
        );
        (new AmbientMenuContext($context))->onControllerArguments($event);

        $resolver = new MenuOptionsResolver(['tenant' => null], $context);
        $options = $resolver->resolve();
        self::assertSame($entity, $options['publication']);
        self::assertArrayNotHasKey('issue', $options, 'a string route parameter is not an entity');
        self::assertArrayNotHasKey('request', $options);

        $explicit = new \stdClass();
        $context->addOptions(['publication' => $explicit]);
        self::assertSame($explicit, $resolver->resolve()['publication'], 'explicit tabler_menu_options() outrank ambient');
    }
}
