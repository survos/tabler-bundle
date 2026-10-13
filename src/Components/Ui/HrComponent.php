<?php

/* src/Components/Ui/HrComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:hr', template: '@SurvosTabler/components/ui/hr.html.twig')]
final class HrComponent
{
    public ?string $position = null;
    public ?string $color = null;
    public ?string $class = null;
    public ?string $text = 'Label';
}
