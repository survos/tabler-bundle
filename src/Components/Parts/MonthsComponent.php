<?php

/* src/Components/Parts/MonthsComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:months', template: '@SurvosTabler/components/parts/months.html.twig')]
final class MonthsComponent
{
    public ?string $value = 'Current';
}
