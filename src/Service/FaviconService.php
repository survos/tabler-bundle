<?php
/* src/Service/FaviconService.php v1.0 - dynamic SVG favicon */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

final class FaviconService
{
    public function __construct(
        private readonly bool $enabled,
        private readonly ?string $text,
        private readonly string $background,
        private readonly string $foreground,
        private readonly string $shape,
        private readonly string $appCode,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function resolvedText(): string
    {
        if ($this->text) {
            return mb_strtoupper($this->text);
        }

        $parts = array_values(array_filter(preg_split('/[-_\s]+/', trim($this->appCode)) ?: []));

        if (count($parts) >= 2) {
            return mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
        }

        return mb_strtoupper(mb_substr($parts[0] ?? $this->appCode, 0, 2));
    }

    public function renderSvg(): string
    {
        $text = htmlspecialchars($this->resolvedText(), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $background = htmlspecialchars($this->background, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $foreground = htmlspecialchars($this->foreground, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $radius = match ($this->shape) {
            'circle' => 32,
            'square' => 0,
            default => 12,
        };

        return <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" role="img" aria-label="{$text}">
              <rect width="64" height="64" rx="{$radius}" fill="{$background}"/>
              <text x="32" y="34" text-anchor="middle" dominant-baseline="central" font-family="system-ui, sans-serif" font-size="30" font-weight="700" fill="{$foreground}">{$text}</text>
            </svg>
            SVG;
    }
}
