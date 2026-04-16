<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

final class MenuOptionsResolver
{
    public function __construct(
        private readonly array $defaultOptions,
        private readonly MenuContext $menuContext,
    ) {}

    public function resolve(array $options = []): array
    {
        return array_merge(
            $this->defaultOptions,
            $this->menuContext->getOptions(),
            $options
        );
    }
}
