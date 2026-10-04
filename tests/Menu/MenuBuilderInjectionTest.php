<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\TestCase;
use Survos\TablerBundle\Menu\MenuBuilderTrait;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\MenuService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\RouterInterface;

final class MenuBuilderInjectionTest extends TestCase
{
    public function testAutowiredListenerNeedsNoConstructorOrBaseClass(): void
    {
        $container = $this->container();
        $container->register(RouterInterface::class)->setSynthetic(true)->setPublic(true);
        $container->register(IconService::class)->setArguments([['building' => 'tabler:building']]);
        $container->register(RouteAliasService::class)
            ->setArguments([['home' => 'app_home'], new Reference(RouterInterface::class)]);
        $container->compile();
        $router = $this->createStub(RouterInterface::class);
        $router->method('generate')->willReturn('/');
        $container->set(RouterInterface::class, $router);

        $builder = $container->get(InjectionTestMenu::class);
        $menu = (new MenuFactory())->createItem('root');
        $item = $builder->add($menu, 'app_home', icon: 'user');
        self::assertNotSame($menu, $item);
        self::assertSame('tabler:user', $item->getExtra('icon'));
        $alias = $builder->addAliased($menu, 'home');
        self::assertNotSame($menu, $alias);
        self::assertSame('tabler:home', $alias->getExtra('icon'));
        self::assertSame('tabler:building', $builder->addSubmenu($menu, 'Buildings', 'building')->getExtra('icon'));
    }

    public function testMissingOptionalHelpersStillAllowExternalLinks(): void
    {
        $container = $this->container();
        $container->compile();
        $builder = $container->get(InjectionTestMenu::class);
        $menu = (new MenuFactory())->createItem('root');
        self::assertSame($menu, $builder->add($menu, 'app_home'));
        $item = $builder->add($menu, uri: 'https://example.org', label: 'External', icon: 'custom:link');
        self::assertSame('https://example.org', $item->getUri());
        self::assertSame('custom:link', $item->getExtra('icon'));
    }

    public function testExistingReadonlyConstructorPropertiesRemainSupported(): void
    {
        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())->method('generate')->with('app_home', [])->willReturn('/');
        $builder = new class($router, new IconService(['user' => 'custom:user'])) {
            use MenuBuilderTrait { add as public; }

            public function __construct(
                private readonly RouterInterface $router,
                private readonly IconService $iconService,
            ) {}
        };
        $builder->setTablerMenuHelpers(iconService: new IconService());
        $menu = (new MenuFactory())->createItem('root');
        $item = $builder->add($menu, 'app_home', icon: 'user');
        self::assertNotSame($menu, $item);
        self::assertSame('custom:user', $item->getExtra('icon'));
    }

    private function container(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->register(MenuService::class)->setArguments([[], null, null, [], null]);
        $container->register(InjectionTestMenu::class)->setAutowired(true)->setPublic(true);
        return $container;
    }
}

final class InjectionTestMenu
{
    use MenuBuilderTrait {
        add as public;
        addAliased as public;
        addSubmenu as public;
    }
}
