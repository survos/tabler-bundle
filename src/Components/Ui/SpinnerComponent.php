<?php

/* src/Components/Ui/SpinnerComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:spinner', template: '@SurvosTabler/components/ui/spinner.html.twig')]
final class SpinnerComponent
{
    public ?string $element = 'div';
    public ?string $type = 'border';
    public ?string $color = null;
    public ?string $class = null;
}
