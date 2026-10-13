<?php

/* src/Components/Parts/InputColorpickerComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:input-colorpicker', template: '@SurvosTabler/components/parts/input-colorpicker.html.twig')]
final class InputColorpickerComponent
{
    public ?string $label = 'Color';
    public ?string $color = 'site.colors.blue.hex';
}
