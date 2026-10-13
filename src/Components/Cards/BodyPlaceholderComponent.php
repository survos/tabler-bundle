<?php

/* src/Components/Cards/BodyPlaceholderComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:body-placeholder', template: '@SurvosTabler/components/cards/body-placeholder.html.twig')]
final class BodyPlaceholderComponent
{
    use DataAwareTrait;

    public ?int $width = 400;
    public ?int $height = 200;
    public ?string $class = null;
    public ?string $ratio = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
