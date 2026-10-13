<?php

/* src/Components/Ui/StatusDotComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:status-dot', template: '@SurvosTabler/components/ui/status-dot.html.twig')]
final class StatusDotComponent
{
    public ?string $color = null;
    public ?string $animated = null;
}
