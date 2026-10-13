<?php

/* src/Components/Ui/TrendingComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:trending', template: '@SurvosTabler/components/ui/trending.html.twig')]
final class TrendingComponent
{
    public ?string $value = null;
    public ?string $class = null;
}
