<?php

/* src/Components/Cards/SmallStats2Component.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:small-stats-2', template: '@SurvosTabler/components/cards/small-stats-2.html.twig')]
final class SmallStats2Component
{
    use DataAwareTrait;

    public ?string $icon = 'user';
    public ?string $growth = null;
    public ?string $color = null;
    public ?bool $light = null;
    public ?string $title = 'Customers';
    public ?int $count = null;
    public ?string $description = 'Since';

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
