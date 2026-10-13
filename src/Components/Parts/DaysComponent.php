<?php

/* src/Components/Parts/DaysComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:days', template: '@SurvosTabler/components/parts/days.html.twig')]
final class DaysComponent
{
    public ?string $id = null;
    public ?string $label = 'Select';
    public ?string $value = 'Last';
}
