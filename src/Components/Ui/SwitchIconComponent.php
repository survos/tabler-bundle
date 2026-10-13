<?php

/* src/Components/Ui/SwitchIconComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:switch-icon', template: '@SurvosTabler/components/ui/switch-icon.html.twig')]
final class SwitchIconComponent
{
    public ?string $icon = 'heart';
    public ?string $iconB = 'icon';
    public ?string $iconAColor = 'muted';
    public ?string $iconBColor = 'red';
    public ?string $iconBClass = null;
    public ?string $variant = null;
    public ?bool $active = null;
}
