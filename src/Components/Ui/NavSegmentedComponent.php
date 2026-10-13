<?php

/* src/Components/Ui/NavSegmentedComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:nav-segmented', template: '@SurvosTabler/components/ui/nav-segmented.html.twig')]
final class NavSegmentedComponent
{
    public ?iterable $items = null;
    public ?string $icons = null;
    public ?bool $disabled = null;
    public ?string $hover = null;
    public ?int $default = 1;
    public ?string $vertical = null;
    public ?int $size = null;
    public ?string $fullWidth = null;
    public ?string $class = null;
    public ?string $name = null;
}
