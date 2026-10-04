<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * The enabled locales as links to the current page in each language. One implementation, used by the
 * `tabler:locale-switcher` component and by LocaleMenuSubscriber (the NAVBAR_LANGUAGE slot), so the
 * path-based (/{_locale}/...) and subdomain-based (fr.example.org) URL rules live in one place.
 */
final class LocaleLinks
{
    private const FLAG_MAP = [
        'en' => 'us',
        'es' => 'mx',
        'uk' => 'ua',
        'hi' => 'in',
        'zh' => 'cn',
        'ja' => 'jp',
        'ko' => 'kr',
        'ar' => 'sa',
        'he' => 'il',
        'fa' => 'ir',
    ];

    /** @param list<string> $enabledLocales */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
        #[Autowire('%kernel.enabled_locales%')]
        private readonly array $enabledLocales = [],
    ) {}

    public function hasMultiple(): bool
    {
        return count($this->enabledLocales) > 1;
    }

    public function current(): string
    {
        return $this->requestStack->getCurrentRequest()?->getLocale() ?? 'en';
    }

    /** @return list<array{code: string, flag: string, url: string, current: bool}> */
    public function all(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$this->hasMultiple() || !$request) {
            return [];
        }

        $hostParts = explode('.', $request->getHttpHost());
        $isSubdomainBased = count($hostParts) === 3 && in_array($hostParts[0], $this->enabledLocales, true);
        $current = $this->current();

        $locales = [];
        foreach ($this->enabledLocales as $locale) {
            $locales[] = [
                'code' => $locale,
                'flag' => self::FLAG_MAP[$locale] ?? $locale,
                'url' => $isSubdomainBased
                    ? $this->subdomainUrl($locale, $hostParts, $request->getPathInfo())
                    : $this->pathUrl($locale),
                'current' => $locale === $current,
            ];
        }

        return $locales;
    }

    /** A language's name in a language ("fr" in "en" is "French"; in "fr" it is "français"). */
    public static function name(string $code, ?string $inLocale = null): string
    {
        try {
            return Languages::getName($code, $inLocale ?? $code);
        } catch (\Throwable) {
            return strtoupper($code);
        }
    }

    private function subdomainUrl(string $locale, array $hostParts, string $pathInfo): string
    {
        $hostParts[0] = $locale;

        return 'https://' . implode('.', $hostParts) . $pathInfo;
    }

    private function pathUrl(string $locale): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $route = $request?->attributes->get('_route');
        if (!$route) {
            return '#';
        }

        return $this->urlGenerator->generate($route, array_merge($request->attributes->get('_route_params', []), ['_locale' => $locale]));
    }
}
