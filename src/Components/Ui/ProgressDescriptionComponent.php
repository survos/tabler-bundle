<?php

/* src/Components/Ui/ProgressDescriptionComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:progress-description', template: '@SurvosTabler/components/ui/progress-description.html.twig')]
final class ProgressDescriptionComponent
{
    public ?string $color = 'blue';
    public ?string $class = null;
    public ?string $label = 'Label';
    public ?string $description = null;
    public ?string $value = null;
}
