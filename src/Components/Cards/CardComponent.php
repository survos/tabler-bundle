<?php

/* src/Components/Cards/CardComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:card', template: '@SurvosTabler/components/cards/card.html.twig')]
final class CardComponent
{
    use DataAwareTrait;

    public ?bool $link = null;
    public ?bool $active = null;
    public ?string $inactive = null;
    public ?string $class = null;
    public ?string $imgTop = null;
    public ?string $statusTop = 'blue';
    public ?string $statusBottom = 'blue';
    public ?string $statusStart = 'blue';
    public ?string $header = null;
    public ?string $headerTitle = 'Header';
    public ?string $headerTabs = null;
    public ?string $headerPills = null;
    public ?string $alert = null;
    public ?string $alertType = 'success';
    public ?string $title = null;
    public ?string $subtitle = null;
    public ?string $body = null;
    public ?string $button = null;
    public ?string $footer = null;
    public ?string $footerButton = null;
    public ?string $footerButtons = null;
    public ?string $footerElements = null;
    public ?string $imgBottom = null;
    public ?string $progress = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
