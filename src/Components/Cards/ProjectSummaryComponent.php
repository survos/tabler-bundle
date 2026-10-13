<?php

/* src/Components/Cards/ProjectSummaryComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:project-summary', template: '@SurvosTabler/components/cards/project-summary.html.twig')]
final class ProjectSummaryComponent
{
    use DataAwareTrait;

    public ?string $projectColor = null;
    public ?string $title = 'New';
    public ?string $date = null;
    public ?string $stageColor = 'green';
    public ?string $stage = 'Waiting';
    public ?string $avatarOffset = null;
    public ?string $avatarLimit = null;
    public ?string $percentage = null;
    public ?string $percentageColor = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
