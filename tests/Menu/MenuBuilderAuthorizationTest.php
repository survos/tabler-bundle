<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use PHPUnit\Framework\TestCase;
use Survos\FieldBundle\Entity\RouteParametersInterface;
use Survos\TablerBundle\Menu\MenuBuilderTrait;
use Survos\TablerBundle\Service\MenuService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class MenuBuilderAuthorizationTest extends TestCase
{
    public function testRestrictedRoutesAreOmittedButPublicAndExternalLinksRemain(): void
    {
        foreach ([false, true] as $admin) {
            $checker = $this->createStub(AuthorizationCheckerInterface::class);
            $checker->method('isGranted')->willReturn($admin);
            $builder = $this->builder(new MenuService(['debug' => ['ROLE_ADMIN']], null, $checker, [], null));
            $menu = (new MenuFactory())->createItem('root');
            $builder->add($menu, 'debug', checkRouteExists: false);
            $builder->add($menu, 'public', checkRouteExists: false);
            $builder->add($menu, uri: 'https://example.org', label: 'External');
            self::assertCount($admin ? 3 : 2, $menu->getChildren());
        }
    }

    public function testVoterReceivesOriginalEntityBeforeRouteParametersAreFlattened(): void
    {
        $entity = $this->createStub(RouteParametersInterface::class);
        $entity->method('getRp')->willReturn(['id' => 42]);
        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $checker->expects(self::once())->method('isGranted')->with('EDIT', self::identicalTo($entity))->willReturn(false);
        $builder = $this->builder(new MenuService(['edit' => ['EDIT']], null, $checker, [], null));
        $menu = (new MenuFactory())->createItem('root');
        self::assertSame($menu, $builder->add($menu, 'edit', $entity, checkRouteExists: false));
        self::assertCount(0, $menu->getChildren());
    }

    public function testEveryRequirementMustPass(): void
    {
        $checker = $this->createStub(AuthorizationCheckerInterface::class);
        $checker->method('isGranted')->willReturnCallback(static fn ($attribute) => $attribute === 'ROLE_ADMIN');
        $builder = $this->builder(new MenuService(['debug' => ['ROLE_ADMIN', 'OTHER_PERMISSION']], null, $checker, [], null));
        $menu = (new MenuFactory())->createItem('root');
        $builder->add($menu, 'debug', checkRouteExists: false);
        self::assertCount(0, $menu->getChildren());
    }

    private function builder(MenuService $service): object
    {
        $builder = new class {
            use MenuBuilderTrait { add as public; }
        };
        $builder->setTablerMenuService($service);
        return $builder;
    }
}
