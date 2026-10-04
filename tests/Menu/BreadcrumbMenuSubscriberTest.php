<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\TestCase;
use Survos\FieldBundle\Enum\Purpose;
use Survos\FieldBundle\Model\RouteMetaDescriptor;
use Survos\FieldBundle\Registry\RouteMetaRegistry;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Menu\BreadcrumbMenuSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class BreadcrumbMenuSubscriberTest extends TestCase
{
    public function testEntitiesBecomeLinkedCrumbsAndTheCurrentPageIsLast(): void
    {
        $paper = new CrumbPaper();
        $issue = new CrumbIssue();

        [$sub, $factory] = $this->subscriber('issue_textsheet', ['paper' => 'daily', 'issue' => '1911-07-06']);
        $menu = $factory->createItem('BREADCRUMB');
        $sub->breadcrumb(new MenuEvent($menu, $factory, ['paper' => $paper, 'alias' => $paper, 'issue' => $issue, 'crumb' => 'ignored']));

        $crumbs = array_values($menu->getChildren());
        self::assertSame(['The Daily', 'July 6, 1911'], array_map(fn ($c) => $c->getLabel(), $crumbs));
        self::assertSame('Browse a paper.', $crumbs[0]->getLinkAttribute('title'));
        self::assertTrue($crumbs[1]->isCurrent(), 'the page itself is the last, unlinked crumb');
        self::assertCount(2, $crumbs, 'two options holding the same paper make one crumb');
    }

    public function testPageThatIsOnlyItselfHasNoTrail(): void
    {
        [$sub, $factory] = $this->subscriber('paper_show', ['paper' => 'daily']);
        $menu = $factory->createItem('BREADCRUMB');
        $sub->breadcrumb(new MenuEvent($menu, $factory, ['paper' => new CrumbPaper()]));
        self::assertFalse($menu->hasChildren());
    }

    public function testComingFromASearchAddsAWayBackAndForeignUrlsAreIgnored(): void
    {
        [$sub, $factory] = $this->subscriber('paper_show', ['paper' => 'daily'], ['returnTo' => '/search']);
        $menu = $factory->createItem('BREADCRUMB');
        $sub->breadcrumb(new MenuEvent($menu, $factory, ['paper' => new CrumbPaper()]));
        $crumbs = array_values($menu->getChildren());
        self::assertSame(['Paper results', 'The Daily'], array_map(fn ($c) => $c->getLabel(), $crumbs));
        self::assertSame('/search', $crumbs[0]->getUri());

        foreach (['//evil.example/x', 'https://evil.example/', '/\\evil'] as $bad) {
            [$sub, $factory] = $this->subscriber('paper_show', ['paper' => 'daily'], ['returnTo' => $bad]);
            $menu = $factory->createItem('BREADCRUMB');
            $sub->breadcrumb(new MenuEvent($menu, $factory, ['paper' => new CrumbPaper()]));
            self::assertFalse($menu->hasChildren(), $bad);
        }
    }

    /** @return array{BreadcrumbMenuSubscriber, MenuFactory} */
    private function subscriber(string $currentRoute, array $routeParams, array $query = []): array
    {
        $routes = new RouteCollection();
        $routes->add('paper_show', new Route('/{paper}'));
        $routes->add('issue_textsheet', new Route('/{paper}/{issue}/textsheet'));

        $router = $this->createStub(RouterInterface::class);
        $router->method('getRouteCollection')->willReturn($routes);
        $router->method('match')->willReturnCallback(static fn (string $path): array => $path === '/search' ? ['_route' => 'search_all'] : throw new \Symfony\Component\Routing\Exception\ResourceNotFoundException());
        $router->method('generate')->willReturnCallback(static fn (string $n, array $p = []): string => '/' . implode('/', $p));

        $request = new Request(query: $query, attributes: ['_route' => $currentRoute, '_route_params' => $routeParams]);
        $stack = new RequestStack();
        $stack->push($request);

        $registry = new RouteMetaRegistry([
            new RouteMetaDescriptor('search_all', '/search', ['GET'], 'C::search', 'Search.', label: 'Paper results'),
            new RouteMetaDescriptor('paper_show', '/{paper}', ['GET'], 'C::paper', 'Browse a paper.', entity: CrumbPaper::class, purpose: Purpose::Show),
            new RouteMetaDescriptor('issue_textsheet', '/{paper}/{issue}/textsheet', ['GET'], 'C::issue', 'Read an issue.', entity: CrumbIssue::class, purpose: Purpose::Show),
        ]);

        return [new BreadcrumbMenuSubscriber($registry, $stack, true, $router), new MenuFactory()];
    }
}

final class CrumbPaper
{
    public string $title = 'The Daily';
}

final class CrumbIssue
{
    public string $label = 'July 6, 1911';
}
