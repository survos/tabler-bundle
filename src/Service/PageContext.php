<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

final class PageContext
{
    private const DEFAULTS_REQUEST_ATTRIBUTE = '_survos_tabler_page_defaults';
    private const OPTIONS_REQUEST_ATTRIBUTE = '_survos_tabler_page_options';

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {}

    public function addDefaults(array $options): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $request->attributes->set(
            self::DEFAULTS_REQUEST_ATTRIBUTE,
            array_merge($this->getDefaults(), $options)
        );
    }

    public function addOptions(array $options): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $request->attributes->set(
            self::OPTIONS_REQUEST_ATTRIBUTE,
            array_merge($this->getOverrides(), $options)
        );
    }

    public function getOptions(): array
    {
        return array_merge($this->getDefaults(), $this->getOverrides());
    }

    private function getDefaults(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return [];
        }

        $options = $request->attributes->get(self::DEFAULTS_REQUEST_ATTRIBUTE, []);

        return is_array($options) ? $options : [];
    }

    private function getOverrides(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return [];
        }

        $options = $request->attributes->get(self::OPTIONS_REQUEST_ATTRIBUTE, []);

        return is_array($options) ? $options : [];
    }
}
