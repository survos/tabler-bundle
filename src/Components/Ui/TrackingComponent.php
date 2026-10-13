<?php

/* src/Components/Ui/TrackingComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:tracking', template: '@SurvosTabler/components/ui/tracking.html.twig')]
final class TrackingComponent
{
    public ?int $blocks = 30;
    public ?string $squares = null;
}
