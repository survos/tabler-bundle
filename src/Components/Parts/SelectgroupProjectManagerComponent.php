<?php

/* src/Components/Parts/SelectgroupProjectManagerComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:selectgroup-project-manager', template: '@SurvosTabler/components/parts/selectgroup-project-manager.html.twig')]
final class SelectgroupProjectManagerComponent
{
    use DataAwareTrait;

    public ?iterable $people = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
