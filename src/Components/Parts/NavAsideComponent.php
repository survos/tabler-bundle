<?php

/* src/Components/Parts/NavAsideComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:nav-aside', template: '@SurvosTabler/components/parts/nav-aside.html.twig')]
final class NavAsideComponent
{
    use DataAwareTrait;

    public ?iterable $items = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
