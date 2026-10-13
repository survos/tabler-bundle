<?php

/* src/Components/Ui/ProgressStepsComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:progress-steps', template: '@SurvosTabler/components/ui/progress-steps.html.twig')]
final class ProgressStepsComponent
{
    public ?int $count = 3;
    public ?string $labels = null;
    public ?bool $active = true;
    public ?string $color = 'primary';
    public ?string $class = null;
    public ?string $id = null;
}
