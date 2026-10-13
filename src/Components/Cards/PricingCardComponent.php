<?php

/* src/Components/Cards/PricingCardComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:pricing-card', template: '@SurvosTabler/components/cards/pricing-card.html.twig')]
final class PricingCardComponent
{
    use DataAwareTrait;

    public ?string $features = null;
    public ?string $featuredColor = null;
    public ?string $category = 'Enterprise';
    public ?string $price = null;
    public ?int $users = 10;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
