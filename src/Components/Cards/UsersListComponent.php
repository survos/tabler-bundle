<?php

/* src/Components/Cards/UsersListComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:users-list', template: '@SurvosTabler/components/cards/users-list.html.twig')]
final class UsersListComponent
{
    use DataAwareTrait;

    public ?bool $hoverable = null;
    public ?string $checkedIds = null;
    public ?string $class = null;
    public ?string $title = 'Last';
    public ?bool $checkbox = null;
    public ?iterable $people = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
