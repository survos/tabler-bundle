<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

/**
 * Icon management with aliases, auto-inference, and style presets.
 * Integrates with Symfony UX Icons.
 */
final class IconService
{
    private const ICON_ALIASES = [
        // Navigation
        'home' => 'tabler:home',
        'dashboard' => 'tabler:dashboard',
        'menu' => 'tabler:menu-2',
        'back' => 'tabler:arrow-left',
        'forward' => 'tabler:arrow-right',

        // Auth
        'login' => 'tabler:login',
        'logout' => 'tabler:logout',
        'register' => 'tabler:user-plus',
        'profile' => 'tabler:user-circle',
        'user' => 'tabler:user',
        'users' => 'tabler:users',
        'password' => 'tabler:lock',

        // CRUD
        'create' => 'tabler:plus',
        'new' => 'tabler:plus',
        'add' => 'tabler:plus',
        'edit' => 'tabler:edit',
        'delete' => 'tabler:trash',
        'remove' => 'tabler:x',
        'save' => 'tabler:device-floppy',
        'cancel' => 'tabler:x',
        'view' => 'tabler:eye',
        'show' => 'tabler:eye',
        'hide' => 'tabler:eye-off',
        'list' => 'tabler:list',
        'index' => 'tabler:list',
        'browse' => 'tabler:list-search',
        'issues' => 'tabler:list-check',
        'grid' => 'tabler:grid-dots',

        // Actions
        'search' => 'tabler:search',
        'filter' => 'tabler:filter',
        'sort' => 'tabler:arrows-sort',
        'refresh' => 'tabler:refresh',
        'sync' => 'tabler:refresh',
        'download' => 'tabler:download',
        'upload' => 'tabler:upload',
        'export' => 'tabler:file-export',
        'import' => 'tabler:file-import',
        'print' => 'tabler:printer',
        'copy' => 'tabler:copy',
        'share' => 'tabler:share',
        'link' => 'tabler:link',
        'external' => 'tabler:external-link',

        // Status
        'success' => 'tabler:check',
        'error' => 'tabler:x',
        'warning' => 'tabler:alert-triangle',
        'info' => 'tabler:info-circle',
        'help' => 'tabler:help',
        'activity' => 'tabler:activity',

        // Settings
        'settings' => 'tabler:settings',
        'config' => 'tabler:adjustments',
        'admin' => 'tabler:shield',
        'tools' => 'tabler:tools',
        'commands' => 'tabler:terminal-2',

        // Content
        'file' => 'tabler:file',
        'folder' => 'tabler:folder',
        'image' => 'tabler:photo',
        'document' => 'tabler:file-text',
        'database' => 'tabler:database',
        'api' => 'tabler:api',
        'code' => 'tabler:code',

        // Social
        'github' => 'tabler:brand-github',
        'twitter' => 'tabler:brand-twitter',
        'facebook' => 'tabler:brand-facebook',
        'linkedin' => 'tabler:brand-linkedin',
        'youtube' => 'tabler:brand-youtube',

        // Misc
        'calendar' => 'tabler:calendar',
        'clock' => 'tabler:clock',
        'location' => 'tabler:map-pin',
        'globe' => 'tabler:world',
        'language' => 'tabler:language',
        'locale' => 'tabler:world',
        'star' => 'tabler:star',
        'heart' => 'tabler:heart',
        'bookmark' => 'tabler:bookmark',
        'notification' => 'tabler:bell',
        'email' => 'tabler:mail',
        'message' => 'tabler:message',

        // Arrows
        'up' => 'tabler:arrow-up',
        'down' => 'tabler:arrow-down',
        'left' => 'tabler:arrow-left',
        'right' => 'tabler:arrow-right',
        'dropdown' => 'tabler:chevron-down',
        'expand' => 'tabler:chevron-down',
        'collapse' => 'tabler:chevron-up',

        // Toggle
        'check' => 'tabler:check',
        'close' => 'tabler:x',
        'plus' => 'tabler:plus',
        'minus' => 'tabler:minus',
        'more' => 'tabler:dots',

        // Layout
        'point' => 'tabler:point',
    ];

    private const STYLE_PRESETS = [
        'success' => ['icon' => 'check', 'class' => 'text-success'],
        'error' => ['icon' => 'x', 'class' => 'text-danger'],
        'danger' => ['icon' => 'alert-triangle', 'class' => 'text-danger'],
        'warning' => ['icon' => 'alert-triangle', 'class' => 'text-warning'],
        'info' => ['icon' => 'info-circle', 'class' => 'text-info'],
        'primary' => ['icon' => 'circle-check', 'class' => 'text-primary'],
        'secondary' => ['icon' => 'circle', 'class' => 'text-secondary'],
        'muted' => ['icon' => 'circle', 'class' => 'text-muted'],

        // Action styles
        'add' => ['icon' => 'plus', 'class' => 'text-success'],
        'create' => ['icon' => 'plus', 'class' => 'text-success'],
        'delete' => ['icon' => 'trash', 'class' => 'text-danger'],
        'remove' => ['icon' => 'x', 'class' => 'text-danger'],
        'edit' => ['icon' => 'edit', 'class' => 'text-warning'],

        // Status styles
        'active' => ['icon' => 'circle-check', 'class' => 'text-success'],
        'inactive' => ['icon' => 'circle-x', 'class' => 'text-muted'],
        'pending' => ['icon' => 'clock', 'class' => 'text-warning'],
        'locked' => ['icon' => 'lock', 'class' => 'text-danger'],
        'unlocked' => ['icon' => 'lock-open', 'class' => 'text-success'],
    ];

    private const ROUTE_SUFFIX_MAP = [
        'index' => 'list',
        'list' => 'list',
        'browse' => 'browse',
        'search' => 'search',
        'show' => 'show',
        'view' => 'view',
        'new' => 'new',
        'create' => 'create',
        'add' => 'add',
        'edit' => 'edit',
        'update' => 'edit',
        'delete' => 'delete',
        'remove' => 'remove',
        'login' => 'login',
        'logout' => 'logout',
        'register' => 'register',
        'profile' => 'profile',
        'settings' => 'settings',
        'admin' => 'admin',
        'dashboard' => 'dashboard',
        'homepage' => 'home',
        'home' => 'home',
        'export' => 'export',
        'import' => 'import',
        'download' => 'download',
        'upload' => 'upload',
    ];

    private array $resolvedAliases;
    private array $resolvedPresets;

    public function __construct(
        private readonly array $configuredAliases = [],
        private readonly array $configuredPresets = [],
    ) {
        $this->resolvedAliases = array_merge(self::ICON_ALIASES, $this->configuredAliases);
        $this->resolvedPresets = array_merge(self::STYLE_PRESETS, $this->configuredPresets);
    }

    /**
     * Resolve icon name from alias or pass-through.
     * Adds default prefix if needed.
     */
    public function resolve(string $icon): string
    {
        // Already has a prefix (e.g., tabler:home, fa6-solid:user)
        if (str_contains($icon, ':')) {
            return $icon;
        }

        // Check alias
        $resolved = $this->resolvedAliases[$icon] ?? $icon;

        // If still no prefix, return as-is — icons must carry explicit prefix
        return $resolved;
    }

    /**
     * Get style preset (icon + class).
     * @return array{icon: string, class: string}|null
     */
    public function getPreset(string $name): ?array
    {
        if (!isset($this->resolvedPresets[$name])) {
            return null;
        }

        $preset = $this->resolvedPresets[$name];

        return [
            'icon' => $this->resolve($preset['icon']),
            'class' => $preset['class'] ?? '',
        ];
    }

    /**
     * Check if alias exists.
     */
    public function has(string $alias): bool
    {
        return isset($this->resolvedAliases[$alias]);
    }

    /**
     * Check if style preset exists.
     */
    public function hasPreset(string $name): bool
    {
        return isset($this->resolvedPresets[$name]);
    }

    /**
     * Infer icon from route name.
     * e.g., 'app_project_edit' => 'tabler:edit', 'museum_index' => 'tabler:list'
     */
    public function inferFromRoute(?string $route): ?string
    {
        if (!$route) {
            return null;
        }

        // Extract last segment: app_project_edit => edit
        $parts = explode('_', $route);
        $suffix = end($parts);

        if (isset(self::ROUTE_SUFFIX_MAP[$suffix])) {
            return $this->resolve(self::ROUTE_SUFFIX_MAP[$suffix]);
        }

        // Try second-to-last for patterns like project_show_details
        if (count($parts) >= 2) {
            $secondLast = $parts[count($parts) - 2];
            if (isset(self::ROUTE_SUFFIX_MAP[$secondLast])) {
                return $this->resolve(self::ROUTE_SUFFIX_MAP[$secondLast]);
            }
        }

        // Check if any part matches an alias
        foreach (array_reverse($parts) as $part) {
            if (isset($this->resolvedAliases[$part])) {
                return $this->resolve($part);
            }
        }

        return null;
    }

    /**
     * Get all resolved aliases.
     */
    public function getAliases(): array
    {
        return $this->resolvedAliases;
    }

    /**
     * Get all resolved presets.
     */
    public function getPresets(): array
    {
        return $this->resolvedPresets;
    }

}
