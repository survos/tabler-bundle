<?php

/* src/Components/Ui/RangeComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:range', template: '@SurvosTabler/components/ui/range.html.twig')]
final class RangeComponent
{
    public ?int $min = 0;
    public ?int $max = 100;
    public ?int $step = 10;
    public ?string $value = null;
    public ?string $id = null;
    public ?string $class = null;
    public ?string $connect = null;
}
