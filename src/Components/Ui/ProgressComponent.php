<?php

/* src/Components/Ui/ProgressComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:progress', template: '@SurvosTabler/components/ui/progress.html.twig')]
final class ProgressComponent
{
    public ?string $value = null;
    public ?string $colors = null;
    public ?string $color = 'blue,red,green,yellow,dark';
    public ?string $class = null;
    public ?int $width = null;
    public ?string $id = null;
    public ?string $indeterminate = null;
    public ?string $values = null;
    public ?string $striped = null;
    public ?string $animated = null;
    public ?string $showValue = null;
}
