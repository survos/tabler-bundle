<?php

/* src/Components/Ui/ShapeComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:shape', template: '@SurvosTabler/components/ui/shape.html.twig')]
final class ShapeComponent
{
    public ?string $color = null;
    public ?string $class = null;
    public ?string $rounded = null;
    public ?string $icon = null;
}
