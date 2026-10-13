<?php

/* src/Components/Ui/IconComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:icon', template: '@SurvosTabler/components/ui/icon.html.twig')]
final class IconComponent
{
    public ?string $icon = null;
    public ?string $type = 'outline';
    public ?string $class = null;
    public ?string $color = null;
    public ?string $inline = null;
    public ?string $size = null;
}
