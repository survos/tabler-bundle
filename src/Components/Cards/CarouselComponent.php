<?php

/* src/Components/Cards/CarouselComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:carousel', template: '@SurvosTabler/components/cards/carousel.html.twig')]
final class CarouselComponent
{
    use DataAwareTrait;

    public ?string $title = 'Carousel';
    public ?string $id = null;
    public ?string $indicators = null;
    public ?string $indicatorsThumb = null;
    public ?string $indicatorsThumbRatio = null;
    public ?string $indicatorsDot = null;
    public ?string $controls = null;
    public ?string $fade = null;
    public ?string $indicatorsVertical = null;
    public ?string $captions = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
