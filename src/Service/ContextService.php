<?php

// wrapper around a public class that can be used from twig or php

namespace Survos\TablerBundle\Service;

use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigTest;

class ContextService
{
    public const THEME_COLORS = [
        "primary",
        "secondary",
        "success",
        "info",
        "warning",
        "danger",
        "light",
        "dark",
        ];

    public function __construct(
        private array $options = [],
        private array $config = []

    )
    {
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): ContextService
    {
        $this->config = $config;
        return $this;
    }

    public function getOption(string $option, string|null $default=null): mixed
    {
        return $this->options[$option]??$default;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    #[AsTwigTest('url')]
    public function isUrl(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $value = trim($value);
        if ($value === '') {
            return false;
        }

        $scheme = parse_url($value, PHP_URL_SCHEME);
        if (!is_string($scheme) || !in_array(strtolower($scheme), ['http', 'https'], true)) {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    #[AsTwigTest('image_url')]
    public function isImageUrl(mixed $value): bool
    {
        if (!$this->isUrl($value)) {
            return false;
        }

        $path = parse_url((string) $value, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            return false;
        }

        return preg_match('/\.(?:jpe?g|png)$/i', $path) === 1;
    }

    #[AsTwigFilter('urlize', isSafe: ['html'])]
    public function urlize(mixed $value): string
    {
        if (!$this->isUrl($value)) {
            return htmlspecialchars(is_scalar($value) ? (string) $value : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        $url = trim((string) $value);
        $escaped = htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return sprintf('<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', $escaped, $escaped);
    }
}
