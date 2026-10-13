<?php

/* src/Components/Ui/ProgressbgComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:progressbg', template: '@SurvosTabler/components/ui/progressbg.html.twig')]
final class ProgressbgComponent
{
    public ?string $value = null;
    public ?string $color = 'primary-lt';
    public ?string $class = null;
    public ?string $text = null;
    public ?string $showValue = null;
}
