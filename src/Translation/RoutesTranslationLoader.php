<?php

namespace Survos\TablerBundle\Translation;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

use function Symfony\Component\String\u;

class RoutesTranslationLoader implements LoaderInterface
{
    public function __construct(
        #[AutowireIterator(tag: 'controller.service_arguments')] private $taggedServices,
    ) {
    }

    /**
     * Loads a locale by going through the methods and looking for #[Route].  If found, it uses the humanized method name for the default translation
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
        $taggedServices = $this->taggedServices; // autowired 'container.service_subscriber'
        foreach ($taggedServices as $controllerClass) {
            $reflectionClass = new \ReflectionClass($controllerClass);
            foreach ($reflectionClass->getMethods() as $method) {
                    foreach ($method->getAttributes(Route::class) as $attribute) {
                        $instance = $attribute->newInstance();
                        $name = $instance->name;
                        if ($name === null) {
                            continue;
                        }
                        $name = str_replace('app_', '', $name);
                        $candidate = u($name)->snake()->replace('_', ' ')->title()->toString();
                        $translations[$instance->name] = $candidate;

                    }
                }
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
