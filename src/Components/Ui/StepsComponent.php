<?php

/* src/Components/Ui/StepsComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:steps', template: '@SurvosTabler/components/ui/steps.html.twig')]
final class StepsComponent
{
    public ?int $count = 4;
    public ?bool $active = true;
    public ?string $numbers = null;
    public ?string $color = null;
    public ?string $showTooltip = null;
    public ?string $showTitle = null;
}
