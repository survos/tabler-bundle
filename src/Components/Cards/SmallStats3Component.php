<?php

/* src/Components/Cards/SmallStats3Component.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:small-stats-3', template: '@SurvosTabler/components/cards/small-stats-3.html.twig')]
final class SmallStats3Component
{
    use DataAwareTrait;

    public ?string $percentage = null;
    public ?string $number = null;
    public ?string $title = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
