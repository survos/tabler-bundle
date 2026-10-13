<?php

/* src/Components/Parts/InputColorComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:input-color', template: '@SurvosTabler/components/parts/input-color.html.twig')]
final class InputColorComponent
{
    public ?string $hideBlackWhite = null;
    public ?string $label = 'Color';
    public ?string $name = 'color';
    public ?string $type = 'radio';
    public ?string $rounded = null;
}
