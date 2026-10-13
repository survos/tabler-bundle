<?php

/* src/Components/Ui/StatusComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:status', template: '@SurvosTabler/components/ui/status.html.twig')]
final class StatusComponent
{
    public ?string $color = null;
    public ?string $lite = null;
    public ?string $dot = null;
    public ?string $animated = null;
    public ?string $text = 'Status';
}
