<?php
/* src/Twig/RouteAliasExtension.php v1.0 - Twig functions for route aliases */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Survos\TablerBundle\Service\RouteAliasService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RouteAliasExtension extends AbstractExtension
{
    public function __construct(
        private readonly RouteAliasService $routeAliasService,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('route_exists', [$this->routeAliasService, 'routeExists']),
            new TwigFunction('tabler_route_exists', [$this->routeAliasService, 'has']),
            new TwigFunction('tabler_url', [$this->routeAliasService, 'generateUrl']),
            new TwigFunction('tabler_path', [$this->routeAliasService, 'generatePath']),
        ];
    }
}
