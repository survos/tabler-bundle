<?php

/* src/Components/PageComponent.php v2.1 - Default horizontal, no sidebar */

declare(strict_types=1);

namespace Survos\TablerBundle\Components;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('tabler:page', template: '@SurvosTabler/components/page.html.twig')]
class PageComponent
{
    public string $layout = 'horizontal';  // Default: horizontal navbar, no sidebar
    public ?string $title = null;
    public ?string $pretitle = null;
    public ?string $transPrefix = null;
    public ?string $translationDomain = null;

    public bool $navbar = true;
    public bool $sidebar = false;  // Default: no sidebar
    public bool $footer = true;
    public bool $dark = false;
    public bool $boxed = false;
    public bool $fluid = false;
    public bool $flashes = true;
    public array $slotOptions = [];

    public ?string $pageClass = null;
    public ?string $containerClass = null;

    /** @var array<array{label: string, url?: string}> */
    public array $breadcrumbs = [];

    public function __construct(
        private readonly string $defaultLayout = 'horizontal',
        private readonly ?TranslatorInterface $translator = null,
    ) {
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        $layout = $data['layout'] ?? $this->defaultLayout;  // Use injected default

        // Layout presets determine sidebar visibility
        $layoutDefaults = match ($layout) {
            'horizontal' => ['sidebar' => false, 'navbar' => true],
            'vertical' => ['sidebar' => true, 'navbar' => true],
            'combo' => ['sidebar' => true, 'navbar' => true],
            'condensed' => ['sidebar' => true, 'boxed' => true, 'navbar' => true],
            'fluid' => ['fluid' => true, 'sidebar' => false, 'navbar' => true],
            'boxed' => ['boxed' => true, 'sidebar' => false, 'navbar' => true],
            'navbar-overlap' => ['sidebar' => false, 'navbar' => true],
            'dashboard' => ['sidebar' => true, 'navbar' => true, 'footer' => true],
            default => ['sidebar' => false, 'navbar' => true],
        };

        // User-provided values override layout defaults
        return array_merge($layoutDefaults, $data);
    }

    #[ExposeInTemplate]
    public function getResolvedTitle(): string
    {
        return $this->title ? $this->translateIfNeeded($this->title) : '';
    }

    #[ExposeInTemplate]
    public function getResolvedPretitle(): string
    {
        return $this->pretitle ? $this->translateIfNeeded($this->pretitle) : '';
    }

    #[ExposeInTemplate]
    public function getContainerClass(): string
    {
        if ($this->containerClass) {
            return $this->containerClass;
        }

        return match (true) {
            $this->boxed => 'container',
            $this->fluid => 'container-fluid',
            default => 'container-xl',
        };
    }

    #[ExposeInTemplate]
    public function getPageClass(): string
    {
        $classes = ['page'];
        if ($this->pageClass) {
            $classes[] = $this->pageClass;
        }
        if ($this->dark) {
            $classes[] = 'theme-dark';
        }

        return implode(' ', $classes);
    }

    private function translateIfNeeded(string $key): string
    {
        if (!$this->translator) {
            return $key;
        }
        $domain = $this->translationDomain;
        if ($this->transPrefix) {
            $key = $this->transPrefix.'.'.$key;
        }

        return $this->translator->trans($key, [], $domain);
    }
}
