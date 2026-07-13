<?php

namespace Survos\TablerBundle\Translation;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

use function Symfony\Component\String\u;

class RoutesTranslationLoader implements LoaderInterface
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    /**
     * Loads a locale by going through the route collection and humanizing each route name.
     *
     * Reads route names straight from the router (no controller reflection/instantiation --
     * the route name is already resolved there, whether it came from a #[Route] attribute or
     * YAML/XML config), so this never touches controller services or their dependencies.
     *
     * Before humanizing, checks common_route_words.php for the route name's last
     * underscore-separated segment (e.g. "tenant_about" -> "about") — a shared dictionary of
     * concepts (about, contact, settings, ...) that recur across apps under different
     * app-specific prefixes, so real non-English wording doesn't have to be re-authored per app.
     *
     * @param mixed  $resource A resource
     * @param string $domain   The domain
     *
     * @return MessageCatalogue A MessageCatalogue instance
     *
     * @throws NotFoundResourceException when the resource cannot be found
     * @throws InvalidResourceException  when the resource cannot be loaded
     */
    public function load(mixed $resource, string $locale, string $domain = 'messages'): MessageCatalogue
    {
        $commonWords = self::commonWords($locale);

        $translations = [];
        foreach ($this->router->getRouteCollection()->all() as $name => $route) {
            $label = str_replace('app_', '', $name);
            $lastSegment = str_contains($label, '_') ? substr($label, strrpos($label, '_') + 1) : $label;

            $translations[$name] = $commonWords[$lastSegment]
                ?? u($label)->snake()->replace('_', ' ')->title()->toString();
        }

        $catalogue = new MessageCatalogue(
            $locale,
            [
                $domain => $translations,
            ]
        );
        return $catalogue;
    }

    /** @return array<string, string> word => translation, for the given locale ([] if none defined) */
    private static function commonWords(string $locale): array
    {
        static $dictionary = null;
        $dictionary ??= require __DIR__ . '/common_route_words.php';

        return $dictionary[$locale] ?? [];
    }

}
