<?php

/* src/Components/Cards/ProjectKanbanComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:project-kanban', template: '@SurvosTabler/components/cards/project-kanban.html.twig')]
final class ProjectKanbanComponent
{
    use DataAwareTrait;

    public ?int $percentage = 20;
    public ?string $percentageColor = 'green';
    public ?string $due = null;
    public ?string $title = 'Task';
    public ?string $badge = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
