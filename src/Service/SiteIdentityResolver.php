<?php

/* src/Service/SiteIdentityResolver.php v1.0 */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Survos\TablerBundle\Model\SiteIdentity;

final class SiteIdentityResolver
{
    /**
     * @param array<string,mixed> $tablerConfig
     */
    public function __construct(
        private array $tablerConfig,
        private readonly ?ContextService $contextService = null,
    ) {
    }

    public function resolve(): SiteIdentity
    {
        /** @var array<string,mixed> $app */
        $app = $this->tablerConfig['app'] ?? [];

        // Optional tenant overlay hook. Implement later in ContextService if desired.
        $overrides = [];
        if ($this->contextService && method_exists($this->contextService, 'getSiteIdentityOverrides')) {
            $maybe = $this->contextService->getSiteIdentityOverrides();
            if (is_array($maybe)) {
                $overrides = $maybe;
            }
        }

        $app = $this->deepMerge($app, $overrides);

        return new SiteIdentity(
            code: (string) ($app['code'] ?? 'my-project'),
            title: (string) ($app['title'] ?? 'My Project'),
            description: (string) ($app['description'] ?? ''),
            abbrHtml: (string) ($app['abbr'] ?? ''),
            logo: is_string($app['logo'] ?? null) ? $app['logo'] : null,
            logoSmall: is_string($app['logo_small'] ?? null) ? $app['logo_small'] : null,
            homepageRoute: is_string($app['homepage_route'] ?? null) ? $app['homepage_route'] : null,
            homepageUrl: is_string($app['homepage_url'] ?? null) ? $app['homepage_url'] : null,
            links: $this->stringMap($app['links'] ?? []),
            social: $this->stringMap($app['social'] ?? []),
            meta: $this->stringMap($app['meta'] ?? []),
            header: is_array($app['header'] ?? null) ? $app['header'] : [],
        );
    }

    /**
     * @return array<string,string>
     */
    private function stringMap(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }
        $out = [];
        foreach ($value as $k => $v) {
            if (is_string($k) && is_string($v) && '' !== $v) {
                $out[$k] = $v;
            }
        }

        return $out;
    }

    /**
     * @param array<string,mixed> $base
     * @param array<string,mixed> $override
     *
     * @return array<string,mixed>
     */
    private function deepMerge(array $base, array $override): array
    {
        foreach ($override as $k => $v) {
            if (!is_string($k)) {
                continue;
            }
            if (array_key_exists($k, $base) && is_array($base[$k]) && is_array($v)) {
                /** @var array<string,mixed> $b */
                $b = $base[$k];
                /** @var array<string,mixed> $o */
                $o = $v;
                $base[$k] = $this->deepMerge($b, $o);
            } else {
                $base[$k] = $v;
            }
        }

        return $base;
    }
}
