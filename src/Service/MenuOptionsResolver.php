<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Survos\MenuBundle\Service\MenuOptionsResolverInterface;

/**
 * What a menu listener sees, lowest precedence first: configured menu_options, the controller's object
 * arguments (AmbientMenuContext), the template's object variables, tabler_menu_options(), the call's options.
 */
final class MenuOptionsResolver implements MenuOptionsResolverInterface
{
    public function __construct(
        private readonly array $defaultOptions,
        private readonly MenuContext $menuContext,
    ) {
    }

    public function resolve(array $options = [], array $templateObjects = []): array
    {
        return array_merge(
            $this->defaultOptions,
            $this->menuContext->getAmbient(),
            $templateObjects,
            $this->menuContext->getOptions(),
            $options
        );
    }
}
