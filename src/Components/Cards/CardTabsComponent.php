<?php

/* src/Components/Cards/CardTabsComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:card-tabs', template: '@SurvosTabler/components/cards/card-tabs.html.twig')]
final class CardTabsComponent
{
    use DataAwareTrait;

    public ?int $count = 3;
    public ?string $id = 'top';
    public ?string $bottom = null;
    public ?string $borderless = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
