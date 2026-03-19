<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Knp\Menu\ItemInterface;
use Survos\TablerBundle\Dto\MenuBadge;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\RouteAliasService;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Menu builder trait with smart icon inference and safe route handling.
 *
 * Classes using this trait should define these properties (via constructor promotion):
 *   - protected readonly ?RouterInterface $router
 *   - protected readonly ?RouteAliasService $routeAliasService
 *   - protected readonly ?IconService $iconService
 */
trait MenuBuilderTrait
{
    // Use static to avoid readonly class conflicts
    private static ?AsciiSlugger $slugger = null;

    private static function slugger(): AsciiSlugger
    {
        return self::$slugger ??= new AsciiSlugger();
    }

    /**
     * Resolve icon - alias, infer from route, or pass-through.
     */
    protected function resolveIcon(?string $icon, ?string $route = null): ?string
    {
        $iconService = $this->iconService ?? null;

        if ($icon !== null) {
            return $iconService?->resolve($icon) ?? $icon;
        }

        if ($route && $iconService) {
            return $iconService->inferFromRoute($route);
        }

        return null;
    }

    /**
     * Check if a route exists.
     */
    protected function routeExists(?string $route): bool
    {
        if (!$route) {
            return false;
        }

        $router = $this->router ?? null;
        if (!$router) {
            return true; // No router, assume it exists
        }

        try {
            $router->generate($route);
            return true;
        } catch (RouteNotFoundException) {
            return false;
        } catch (\Exception) {
            // Route exists but requires parameters
            return true;
        }
    }

    /**
     * Add menu item with smart icon inference.
     * If route doesn't exist, the item is NOT added (safe by default).
     */
    protected function add(
        ItemInterface $menu,
        ?string $route = null,
        array $rp = [],
        ?string $label = null,
        ?string $uri = null,
        ?string $icon = null,
        ?string $id = null, // this was used in to set an element id, e.g. dropdowns
        ?string $style = null, // from bootstrap, not sure it's the best approach
        MenuBadge|string|int|array|null $badge = null,
        bool $external = false,
        bool $dividerBefore = false,
        bool $dividerAfter = false,
        ?string $translationDomain = 'messages',
        bool $if = true,
        bool $inferIcon = true,
        bool $checkRouteExists = true,
    ): ItemInterface {
        if (!$if) {
            return $menu;
        }

        if ($route) {
            assert($this->routeExists($route), "Missing route $route");
        }
        // Skip if route doesn't exist (safe by default)
        if ($route && $checkRouteExists && !$this->routeExists($route)) {
            return $menu;
        }

        $label ??= $this->routeToLabel($route ?? $uri ?? '');
        $id = self::slugger()->slug($label ?: 'item')->toString() . '_' . bin2hex(random_bytes(4));

        $child = $menu->addChild($id, array_filter([
            'route' => $route,
            'routeParameters' => $rp ?: null,
            'uri' => $uri,
            'label' => $label,
        ], fn($v) => $v !== null));

        // Resolve or infer icon
        $resolvedIcon = $inferIcon
            ? $this->resolveIcon($icon, $route)
            : ($icon ? $this->resolveIcon($icon) : null);

        if ($resolvedIcon) {
            $child->setExtra('icon', $resolvedIcon);
        }

        if ($badgeDto = MenuBadge::fromMixed($badge)) {
            $child->setExtra('badge', $badgeDto);
        }

        $iconService = $this->iconService ?? null;
        if ($external || ($uri && str_starts_with($uri, 'http'))) {
            $child->setLinkAttribute('target', '_blank');
            if (!$resolvedIcon) {
                $child->setExtra('icon', $iconService?->resolve('external') ?? 'tabler:external-link');
            }
        }

        if ($dividerBefore) {
            $child->setAttribute('divider_prepend', true);
        }

        if ($dividerAfter) {
            $child->setAttribute('divider_append', true);
        }

        if ($translationDomain) {
            $child->setExtra('translation_domain', $translationDomain);
        }

        $child->setExtra('safe_label', true);

        return $child;
    }

    /**
     * Add item using a route alias with automatic icon inference.
     * Safe: only adds if the aliased route exists.
     */
    protected function addAliased(
        ItemInterface $menu,
        string $alias,
        array $rp = [],
        ?string $label = null,
        ?string $icon = null,
    ): ItemInterface {
        $routeAliasService = $this->routeAliasService ?? null;

        if (!$routeAliasService?->has($alias)) {
            return $menu;
        }

        $route = $routeAliasService->get($alias);
        $label ??= ucfirst($alias);
        $icon ??= $alias;

        return $this->add($menu, $route, $rp, $label, icon: $icon, checkRouteExists: false);
    }

    protected function addSubmenu(
        ItemInterface $menu,
        string $label,
        ?string $icon = null,
        ?string $translationDomain = 'messages',
    ): ItemInterface {
        $id = self::slugger()->slug($label)->toString() . '_' . bin2hex(random_bytes(4));

        $child = $menu->addChild($id, ['label' => $label]);
        $child->setExtra('submenu', true);
        $child->setExtra('safe_label', true);

        $iconService = $this->iconService ?? null;
        if ($icon) {
            $child->setExtra('icon', $iconService?->resolve($icon) ?? $icon);
        }

        if ($translationDomain) {
            $child->setExtra('translation_domain', $translationDomain);
        }

        return $child;
    }

    protected function addHeading(
        ItemInterface $menu,
        string $label,
        ?string $icon = null,
    ): ItemInterface {
        $child = $this->add($menu, label: $label, icon: $icon, inferIcon: false, checkRouteExists: false);
        $child->setExtra('heading', true);
        $child->setAttribute('class', 'menu-heading');
        return $child;
    }

    protected function addDivider(ItemInterface $menu): ItemInterface
    {
        $child = $menu->addChild('divider_' . bin2hex(random_bytes(4)));
        $child->setExtra('divider', true);
        return $child;
    }

    private function routeToLabel(string $route): string
    {
        if (!$route) {
            return '';
        }
        $label = preg_replace('/^(app_|admin_|survos_)/', '', $route);
        $label = preg_replace('/_(index|show|edit|new)$/', '', $label);
        return ucwords(str_replace('_', ' ', $label));
    }
}
