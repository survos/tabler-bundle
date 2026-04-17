<?php
/* src/Components/Ui/AccordionComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Survos\TablerBundle\Service\IconService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsTwigComponent(name: 'ui:accordion', template: '@SurvosTabler/components/ui/accordion.html.twig')]
final class AccordionComponent
{
    use DataAwareTrait;

    public ?int $count = 4;
    public ?string $id = 'default';
    public ?string $toggleIcon = 'chevron-down';
    public ?string $type = null;
    public bool|string|null $showIcon = null;
    public ?array $entries = null;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
        private readonly ?IconService $iconService = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }

    #[ExposeInTemplate]
    public function getResolvedToggleIcon(): ?string
    {
        return $this->resolveIcon($this->toggleIcon);
    }

    #[ExposeInTemplate]
    public function getResolvedShowIcon(): ?string
    {
        if ($this->showIcon === null || $this->showIcon === false) {
            return null;
        }

        if ($this->showIcon === true) {
            return $this->resolveIcon('link');
        }

        return $this->resolveIcon($this->showIcon);
    }

    private function resolveIcon(?string $icon): ?string
    {
        if (!$icon) {
            return null;
        }

        return $this->iconService?->resolve($icon) ?? $icon;
    }
}
