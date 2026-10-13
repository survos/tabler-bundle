<?php

/* src/Components/Ui/StarsComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:stars', template: '@SurvosTabler/components/ui/stars.html.twig')]
final class StarsComponent
{
    public ?int $count = 5;
    public ?int $rate = 4;
    public ?string $icon = 'star';
    public ?string $color = 'yellow';
    public ?bool $filled = null;
}
