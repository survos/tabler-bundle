<?php

/* src/Components/Ui/AlertComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:alert', template: '@SurvosTabler/components/ui/alert.html.twig')]
final class AlertComponent
{
    use DataAwareTrait;

    public ?string $icon = null;
    public ?string $type = 'success';
    public ?bool $important = null;
    public ?bool $minor = null;
    public ?string $showClose = null;
    public ?string $avatar = null;
    public ?string $description = 'description';
    public ?array $list = null;
    public ?string $title = 'This';
    public ?string $action = 'Action';
    public ?string $link = 'Link';
    public ?string $buttons = null;
    public ?iterable $items = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
