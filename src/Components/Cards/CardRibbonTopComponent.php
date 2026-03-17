<?php
/* src/Components/Cards/CardRibbonTopComponent.php v4.8 - Generated 2026-03-17 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsTwigComponent(name: 'cards:card-ribbon-top', template: '@SurvosTabler/components/cards/card-ribbon-top.html.twig')]
final class CardRibbonTopComponent
{
    use DataAwareTrait;


    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}