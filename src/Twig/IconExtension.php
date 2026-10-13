<?php

/* src/Twig/IconExtension.php v1.6 - Properly calls UX Icons runtime */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Survos\TablerBundle\Service\IconService;
use Symfony\UX\Icons\Twig\UXIconRuntime;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class IconExtension extends AbstractExtension
{
    public function __construct(
        private readonly IconService $iconService,
        private readonly Environment $twig,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [$this, 'renderIcon'], ['is_safe' => ['html']]),
        ];
    }

    public function renderIcon(?string $name, array $attributes = []): string
    {
        if (!$name) {
            return '';
        }

        // Use IconService to resolve aliases and add prefix
        $resolvedName = $this->iconService->resolve($name);

        // Get UX Icons runtime and call renderIcon
        try {
            $runtime = $this->twig->getRuntime(UXIconRuntime::class);

            return $runtime->renderIcon($resolvedName, $attributes);
        } catch (\Exception $e) {
            // Fallback if UX Icons not available
            $class = $attributes['class'] ?? 'icon';

            return sprintf('<span data-icon="%s" class="%s">[%s]</span>', $resolvedName, $class, $resolvedName);
        }
    }
}
