<?php
/* src/Service/RouteAliasService.php v1.0 - Route alias management with URL generation */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RouteAliasService
{
    private array $resolvedRoutes = [];

    public function __construct(
        private readonly array $configuredAliases,
        private readonly RouterInterface $router,
    ) {
        // Pre-validate routes at construction
        foreach ($configuredAliases as $alias => $routeName) {
            if ($this->routeExists($routeName)) {
                $this->resolvedRoutes[$alias] = $routeName;
            }
        }
    }

    /**
     * Check if an alias exists and has a valid route.
     */
    public function has(string $alias): bool
    {
        return isset($this->resolvedRoutes[$alias]);
    }

    /**
     * Get the route name for an alias.
     */
    public function get(string $alias): ?string
    {
        return $this->resolvedRoutes[$alias] ?? null;
    }

    /**
     * Generate URL for an alias.
     */
    public function generateUrl(string $alias, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): ?string
    {
        $route = $this->get($alias);
        if (!$route) {
            return null;
        }

        try {
            return $this->router->generate($route, $parameters, $referenceType);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Generate path for an alias.
     */
    public function generatePath(string $alias, array $parameters = []): ?string
    {
        return $this->generateUrl($alias, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * Get all resolved aliases.
     */
    public function getAll(): array
    {
        return $this->resolvedRoutes;
    }

    public function routeExists(?string $routeName, array $parameters = []): bool
    {
        if (!$routeName) {
            return false;
        }

        try {
            $this->router->generate($routeName, $parameters);
            return true;
        } catch (RouteNotFoundException) {
            return false;
        } catch (\Exception) {
            // Route exists but requires parameters
            return true;
        }
    }
}
