<?php

/* src/Components/Ui/DropdownMenuComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:dropdown-menu', template: '@SurvosTabler/components/ui/dropdown-menu.html.twig')]
final class DropdownMenuComponent
{
    use DataAwareTrait;

    public ?string $right = null;
    public ?bool $show = null;
    public ?string $arrow = null;
    public ?string $class = null;
    public ?bool $dark = null;
    public ?string $menu = null;
    public ?string $check = null;
    public ?string $radio = null;
    public ?array $people = null;
    public ?string $flag = null;
    public ?string $type = null;
    public ?string $header = null;
    public ?string $icons = null;
    public ?string $badge = null;
    public ?bool $active = null;
    public ?bool $disabled = null;
    public ?string $separated = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
