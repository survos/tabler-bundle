<?php

/* src/Components/Cards/HeatmapComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:heatmap', template: '@SurvosTabler/components/cards/heatmap.html.twig')]
final class HeatmapComponent
{
    use DataAwareTrait;

    public ?string $title = null;
    public ?string $id = null;
    public ?string $colors = null;
    public ?string $scale = null;
    public ?string $labels = null;
    public ?string $noShades = null;
    public ?int $count = null;
    public ?string $color = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
