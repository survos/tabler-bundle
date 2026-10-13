<?php

/* src/Components/Cards/WeatherComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:weather', template: '@SurvosTabler/components/cards/weather.html.twig')]
final class WeatherComponent
{
    use DataAwareTrait;

    public ?string $icon = 'cloud-rain';
    public ?string $color = 'blue';
    public ?string $city = 'Warsaw';
    public ?string $description = 'Cloudy';
    public ?int $temperature = 20;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
