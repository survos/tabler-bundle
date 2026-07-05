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
        $translations = [];
        foreach ($this->router->getRouteCollection()->all() as $name => $route) {
            $label = str_replace('app_', '', $name);
            $translations[$name] = u($label)->snake()->replace('_', ' ')->title()->toString();
        }

        $catalogue = new MessageCatalogue(
            $locale,
            [
                $domain => $translations,
            ]
        );
        return $catalogue;
    }

}
