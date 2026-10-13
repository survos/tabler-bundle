<?php

/* src/Components/Ui/SvgComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:svg', template: '@SurvosTabler/components/ui/svg.html.twig')]
final class SvgComponent
{
    public ?int $width = null;
    public ?int $size = 20;
    public ?int $height = null;
    public ?string $class = null;
    public ?string $ratio = null;
    public ?string $border = null;
}
