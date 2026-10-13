<?php

/* src/Components/Cards/RibbonComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:ribbon', template: '@SurvosTabler/components/cards/ribbon.html.twig')]
final class RibbonComponent
{
    use DataAwareTrait;

    public ?int $productId = 0;
    public ?string $lorem = null;
    public ?string $color = null;
    public ?string $top = null;
    public ?string $left = null;
    public ?string $bottom = null;
    public ?string $bookmark = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
