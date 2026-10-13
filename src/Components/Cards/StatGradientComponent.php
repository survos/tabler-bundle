<?php

/* src/Components/Cards/StatGradientComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:stat-gradient', template: '@SurvosTabler/components/cards/stat-gradient.html.twig')]
final class StatGradientComponent
{
    use DataAwareTrait;

    public ?string $color = 'primary';
    public ?string $icon = 'trending-up';
    public ?string $title = 'Total';
    public ?string $value = null;
    public ?int $progress = 0;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
