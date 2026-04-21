<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Survos\TablerBundle\Model\Tab;
use Survos\TablerBundle\Service\ContextService;
use Survos\TablerBundle\Service\PageContext;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\ComponentRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function __construct(
        #[Autowire(service: 'ux.twig_component.component_renderer')]
        private ComponentRenderer $componentRenderer,
        private array $routes,
        private array $options,
        private array $config,
        private ContextService $contextService,
        private PageContext $pageContext,
    ) {
    }

    public function render(string $name, array $props = []): string
    {
        return $this->componentRenderer->createAndRender($name, $props);
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('attributes', [$this, 'attributes'], ['is_safe' => ['html']]),
            new TwigFilter('tabler_container', [$this, 'containerClass'], ['is_safe' => ['html']]),
            new TwigFilter('route_alias', fn (string $routeName): ?string =>
                ($this->routes[$routeName] === false)
                    ? null
                    : $this->routes[$routeName] ?? $routeName),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bootstrap_theme_colors', fn () => ContextService::THEME_COLORS),
            new TwigFunction('theme_option', fn (string $option) => $this->contextService->getOption($option)),
            new TwigFunction('config', fn () => $this->config),
            new TwigFunction('tab', fn (string $label, ?string $content = null, ?string $translationDomain = null) => new Tab($label, $content, $translationDomain)),
            new TwigFunction('theme_options', fn () => $this->contextService->getOptions()),
            new TwigFunction('hasOffcanvas', fn () => $this->contextService->getOption('offcanvas')),
            new TwigFunction('admin_context_is_enabled', [$this, 'isEnabled']),
            new TwigFunction('badge', [$this, 'badge']),
            new TwigFunction('attributes', [$this, 'attributes'], ['is_safe' => ['html']]),
            new TwigFunction('img', fn (string $src) => sprintf('img src="%s"', $src)),
            new TwigFunction('meta', [$this, 'setMeta'], ['is_safe' => ['html']]),
            new TwigFunction('page', [$this, 'setPage'], ['is_safe' => ['html']]),
            new TwigFunction('page_context', [$this, 'getPageContext']),
        ];
    }

    public function setMeta(array $options = [], mixed $caller = null): string
    {
        return $this->setPage($options, $caller);
    }

    public function setPage(array $options = [], mixed $caller = null): string
    {
        if ($caller !== null) {
            $options['caller'] = is_scalar($caller) ? (string) $caller : get_debug_type($caller);
        }

        $normalized = $this->normalizePageOptions($options);
        $defaults = $normalized['defaults'] ?? null;
        unset($normalized['defaults']);

        if (is_array($defaults) && $defaults !== []) {
            $this->pageContext->addDefaults($defaults);
        }

        if ($normalized !== []) {
            $this->pageContext->addOptions($normalized);
        }

        return '';
    }

    public function getPageContext(): array
    {
        return $this->pageContext->getOptions();
    }

    public function containerClass(string $class = ''): string
    {
        $classList = explode(' ', $class);
        $classList[] = 'container-fluid';

        return trim(implode(' ', array_values($classList)));
    }

    public function badge(array $props = []): string
    {
        return $this->render('badge', $props);
    }

    public function isEnabled(string $value): bool
    {
        return $this->options[$value] ?? false;
    }

    public function icon(string $value, string $extra = '', string $title = ''): string
    {
        return sprintf('<span class="%s %s" title="%s" ></span>', $value, $extra, $title);
    }

    public function attributes(array $value): string
    {
        $attrs = [];
        foreach ($value as $k => $v) {
            if (is_string($v) && $v) {
                $attrs[] = sprintf(' %s="%s"', $k, $v);
            }
        }

        return join("\n", $attrs);
    }

    private function normalizePageOptions(array $options): array
    {
        $normalized = $options;

        foreach ([
            'preTitle' => 'pretitle',
            'subTitle' => 'subtitle',
            'showHeader' => 'show_page_header',
            'showPageHeader' => 'show_page_header',
            'containerClass' => 'container_class',
        ] as $from => $to) {
            if (array_key_exists($from, $normalized) && !array_key_exists($to, $normalized)) {
                $normalized[$to] = $normalized[$from];
            }
        }

        if (isset($normalized['defaults']) && is_array($normalized['defaults'])) {
            $normalized['defaults'] = $this->normalizePageOptions($normalized['defaults']);
        }

        if (array_key_exists('container', $normalized) && !array_key_exists('container_class', $normalized)) {
            $normalized['container_class'] = $this->normalizeContainer((string) $normalized['container']);
        }

        return $normalized;
    }

    private function normalizeContainer(string $container): string
    {
        return match ($container) {
            '', 'default', 'xl' => 'container-xl',
            'fluid' => 'container-fluid',
            'lg' => 'container-lg',
            'md' => 'container-md',
            'sm' => 'container-sm',
            default => str_starts_with($container, 'container-') ? $container : 'container-xl',
        };
    }
}
