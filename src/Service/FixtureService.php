<?php

/* src/Service/FixtureService.php v1.0 - Load JSON fixture data */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

class FixtureService
{
    private array $cache = [];

    public function __construct(
        private readonly string $fixturesPath,
        private readonly string $assetsBaseUrl,
    ) {
    }

    /**
     * Load fixture data by name (e.g., 'people', 'commits').
     */
    public function load(string $name): array
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $file = $this->fixturesPath.'/'.$name.'.json';

        if (!file_exists($file)) {
            return [];
        }

        $data = json_decode(file_get_contents($file), true) ?? [];

        // Process image paths to use configured base URL
        $data = $this->processImagePaths($data);

        $this->cache[$name] = $data;

        return $data;
    }

    /**
     * Get multiple fixtures at once.
     */
    public function loadMany(array $names): array
    {
        $result = [];
        foreach ($names as $name) {
            $result[$name] = $this->load($name);
        }

        return $result;
    }

    /**
     * Resolve asset path to full URL.
     */
    public function assetUrl(string $path): string
    {
        // Already absolute URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Remove 'static/' prefix if present (Tabler's internal path)
        $path = preg_replace('#^static/#', '', $path);

        return rtrim($this->assetsBaseUrl, '/').'/'.ltrim($path, '/');
    }

    /**
     * Process array recursively to convert image paths.
     */
    private function processImagePaths(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->processImagePaths($value);
            } elseif (is_string($value) && $this->looksLikeImagePath($value)) {
                $data[$key] = $this->assetUrl($value);
            }
        }

        return $data;
    }

    private function looksLikeImagePath(string $value): bool
    {
        return (bool) preg_match('#\.(jpg|jpeg|png|gif|webp|svg)$#i', $value);
    }

    public function getFixturesPath(): string
    {
        return $this->fixturesPath;
    }

    public function getAssetsBaseUrl(): string
    {
        return $this->assetsBaseUrl;
    }
}
