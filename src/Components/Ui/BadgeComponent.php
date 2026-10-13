<?php

/* src/Components/Ui/BadgeComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:badge', template: '@SurvosTabler/components/ui/badge.html.twig')]
final class BadgeComponent
{
    public ?string $scale = null;
    public ?string $color = null;
    public ?bool $light = null;
    public ?string $class = null;
    public ?string $icon = null;
    public ?string $personId = null;
    public ?string $text = null;
    public ?string $addon = null;
    public ?string $addonColor = null;
}
