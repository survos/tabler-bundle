<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

/**
 * Manages icon aliases with sensible defaults.
 * Uses tabler icons by default, but allows override via config.
 */
final class IconAliasService
{
    private const DEFAULTS = [
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
        'key' => 'tabler:key',

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
        'grid' => 'tabler:grid-dots',

        // Actions
        'search' => 'tabler:search',
        'filter' => 'tabler:filter',
        'sort' => 'tabler:arrows-sort',
        'refresh' => 'tabler:refresh',
        'reload' => 'tabler:reload',
        'sync' => 'tabler:refresh',
        'download' => 'tabler:download',
        'upload' => 'tabler:upload',
        'export' => 'tabler:file-export',
        'import' => 'tabler:file-import',
        'print' => 'tabler:printer',
        'copy' => 'tabler:copy',
        'paste' => 'tabler:clipboard',
        'share' => 'tabler:share',
        'link' => 'tabler:link',
        'external' => 'tabler:external-link',

        // Status
        'success' => 'tabler:check',
        'error' => 'tabler:x',
        'warning' => 'tabler:alert-triangle',
        'info' => 'tabler:info-circle',
        'help' => 'tabler:help',
        'question' => 'tabler:question-mark',

        // Content
        'file' => 'tabler:file',
        'folder' => 'tabler:folder',
        'image' => 'tabler:photo',
        'video' => 'tabler:video',
        'audio' => 'tabler:music',
        'document' => 'tabler:file-text',
        'pdf' => 'tabler:file-type-pdf',
        'spreadsheet' => 'tabler:table',
        'archive' => 'tabler:archive',

        // Communication
        'email' => 'tabler:mail',
        'message' => 'tabler:message',
        'chat' => 'tabler:message-circle',
        'comment' => 'tabler:message-2',
        'notification' => 'tabler:bell',
        'phone' => 'tabler:phone',

        // Settings
        'settings' => 'tabler:settings',
        'config' => 'tabler:adjustments',
        'preferences' => 'tabler:adjustments-horizontal',
        'admin' => 'tabler:shield',
        'tools' => 'tabler:tools',

        // Data
        'database' => 'tabler:database',
        'api' => 'tabler:api',
        'code' => 'tabler:code',
        'terminal' => 'tabler:terminal-2',

        // Social
        'github' => 'tabler:brand-github',
        'twitter' => 'tabler:brand-twitter',
        'facebook' => 'tabler:brand-facebook',
        'linkedin' => 'tabler:brand-linkedin',
        'youtube' => 'tabler:brand-youtube',
        'instagram' => 'tabler:brand-instagram',

        // Misc
        'calendar' => 'tabler:calendar',
        'clock' => 'tabler:clock',
        'location' => 'tabler:map-pin',
        'map' => 'tabler:map',
        'star' => 'tabler:star',
        'heart' => 'tabler:heart',
        'bookmark' => 'tabler:bookmark',
        'tag' => 'tabler:tag',
        'flag' => 'tabler:flag',
        'globe' => 'tabler:world',
        'language' => 'tabler:language',
        'locale' => 'tabler:world',

        // Layout
        'expand' => 'tabler:arrows-maximize',
        'collapse' => 'tabler:arrows-minimize',
        'fullscreen' => 'tabler:maximize',
        'sidebar' => 'tabler:layout-sidebar',
        'columns' => 'tabler:columns',
        'rows' => 'tabler:rows',

        // State
        'loading' => 'tabler:loader',
        'spinner' => 'tabler:loader-2',
        'empty' => 'tabler:inbox',
        'locked' => 'tabler:lock',
        'unlocked' => 'tabler:lock-open',
        'private' => 'tabler:eye-off',
        'public' => 'tabler:world',

        // Arrows/Direction
        'up' => 'tabler:arrow-up',
        'down' => 'tabler:arrow-down',
        'left' => 'tabler:arrow-left',
        'right' => 'tabler:arrow-right',
        'chevron-up' => 'tabler:chevron-up',
        'chevron-down' => 'tabler:chevron-down',
        'chevron-left' => 'tabler:chevron-left',
        'chevron-right' => 'tabler:chevron-right',
        'caret-up' => 'tabler:caret-up',
        'caret-down' => 'tabler:caret-down',
        'dropdown' => 'tabler:chevron-down',

        // Toggle
        'check' => 'tabler:check',
        'close' => 'tabler:x',
        'plus' => 'tabler:plus',
        'minus' => 'tabler:minus',
        'more' => 'tabler:dots',
        'more-vertical' => 'tabler:dots-vertical',
    ];

    private array $resolved;

    /**
     * @param array<string, string> $configuredAliases Overrides from config
     */
    public function __construct(
        private readonly array $configuredAliases = [],
    ) {
        $this->resolved = array_merge(self::DEFAULTS, $this->configuredAliases);
    }

    /**
     * Get icon for alias. Returns the alias itself if not found (pass-through).
     */
    public function get(string $alias): string
    {
        return $this->resolved[$alias] ?? $alias;
    }

    /**
     * Check if alias is defined.
     */
    public function has(string $alias): bool
    {
        return isset($this->resolved[$alias]);
    }

    /**
     * Get all resolved aliases.
     *
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->resolved;
    }

    /**
     * Get default icons (before config overrides).
     *
     * @return array<string, string>
     */
    public static function getDefaults(): array
    {
        return self::DEFAULTS;
    }

    /**
     * Resolve icon - if it looks like an alias (no prefix), resolve it.
     * If it already has a prefix (tabler:, fa:, etc.), return as-is.
     */
    public function resolve(string $icon): string
    {
        // Already has a prefix, return as-is
        if (str_contains($icon, ':')) {
            return $icon;
        }

        // Try to resolve as alias
        return $this->get($icon);
    }
}
