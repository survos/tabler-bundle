<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

final class MenuContext
{
    private const REQUEST_ATTRIBUTE = '_survos_tabler_menu_options';
    private const AMBIENT_ATTRIBUTE = '_survos_tabler_menu_ambient';

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {}

    public function addOptions(array $options): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $request->attributes->set(
            self::REQUEST_ATTRIBUTE,
            array_merge($this->getOptions(), $options)
        );
    }

    /**
     * Objects the controller received (see AmbientMenuContext). Kept apart from addOptions() so that anything set
     * explicitly, or read from the template, outranks them.
     */
    public function addAmbient(array $options): void
    {
        $this->requestStack->getCurrentRequest()?->attributes->set(self::AMBIENT_ATTRIBUTE, array_merge($this->getAmbient(), $options));
    }

    public function getAmbient(): array
    {
        $ambient = $this->requestStack->getCurrentRequest()?->attributes->get(self::AMBIENT_ATTRIBUTE, []);

        return is_array($ambient) ? $ambient : [];
    }

    public function getOptions(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return [];
        }

        $options = $request->attributes->get(self::REQUEST_ATTRIBUTE, []);

        return is_array($options) ? $options : [];
    }

    public function clear(): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $request->attributes->remove(self::REQUEST_ATTRIBUTE);
    }
}
