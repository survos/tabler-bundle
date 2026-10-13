<?php

/* src/Components/Cards/SmallStatsComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:small-stats', template: '@SurvosTabler/components/cards/small-stats.html.twig')]
final class SmallStatsComponent
{
    use DataAwareTrait;

    public ?string $chartType = 'line';
    public ?string $chartPosition = 'right';
    public ?string $class = null;
    public ?string $icon = null;
    public ?string $color = null;
    public ?string $lt = null;
    public ?string $personId = null;
    public ?string $chartData = null;
    public ?string $id = null;
    public ?string $chartLabel = null;
    public ?string $chartLabelIcon = null;
    public ?string $title = null;
    public ?string $smallIcon = null;
    public ?string $descriptionValue = null;
    public ?string $descriptionValueColor = 'green';
    public ?string $description = 'Users';

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
