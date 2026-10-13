<?php

/* src/Components/Ui/StatusIndicatorComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:status-indicator', template: '@SurvosTabler/components/ui/status-indicator.html.twig')]
final class StatusIndicatorComponent
{
    public ?string $color = null;
    public ?string $animated = null;
}
