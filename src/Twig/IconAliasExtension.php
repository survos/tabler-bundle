<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Survos\TablerBundle\Service\IconAliasService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class IconAliasExtension extends AbstractExtension
{
    public function __construct(
        private readonly IconAliasService $iconAliasService,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            // Get resolved icon name for use with ux_icon()
            new TwigFunction('tabler_icon', $this->getIcon(...)),

            // Check if alias exists
            new TwigFunction('tabler_icon_exists', $this->iconExists(...)),

            // Render icon directly (convenience wrapper around ux_icon)
            new TwigFunction('icon', $this->renderIcon(...), ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get resolved icon name.
     * Usage: {{ ux_icon(tabler_icon('edit')) }}.
     */
    public function getIcon(string $alias): string
    {
        return $this->iconAliasService->resolve($alias);
    }

    public function iconExists(string $alias): bool
    {
        return $this->iconAliasService->has($alias);
    }

    /**
     * Render icon directly using ux_icon.
     * Usage: {{ icon('edit', {class: 'icon-lg'}) }}.
     */
    public function renderIcon(string $alias, array $attributes = []): string
    {
        $icon = $this->iconAliasService->resolve($alias);

        // We return the icon name - actual rendering happens via ux_icon in template
        // This is a workaround since we can't call ux_icon from PHP directly
        $class = $attributes['class'] ?? 'icon';
        unset($attributes['class']);

        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= sprintf(' %s="%s"', $key, htmlspecialchars((string) $value));
        }

        // Return markup that works with Symfony UX Icons
        return sprintf('<span data-icon="%s" class="%s"%s></span>', $icon, $class, $attrString);
    }
}
