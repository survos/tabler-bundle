<?php

/* src/Components/Ui/SelectComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:select', template: '@SurvosTabler/components/ui/select.html.twig')]
final class SelectComponent
{
    use DataAwareTrait;

    public ?string $id = null;
    public ?string $key = 'people';
    public ?string $value = null;
    public ?string $class = null;
    public ?string $state = null;
    public ?string $placeholder = null;
    public ?string $multiple = null;
    public ?string $values = null;
    public ?string $indicator = null;
    public ?string $showSearch = null;
    public ?string $showScripts = null;
    public ?iterable $people = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
