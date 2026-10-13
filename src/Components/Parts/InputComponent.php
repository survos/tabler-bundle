<?php

/* src/Components/Parts/InputComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:input', template: '@SurvosTabler/components/parts/input.html.twig')]
final class InputComponent
{
    public ?string $type = null;
    public ?string $label = 'Static';
    public ?string $value = 'Input';
    public ?string $placeholder = 'Input';
}
