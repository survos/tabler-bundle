<?php

/* src/Components/Ui/InlinePlayerComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:inline-player', template: '@SurvosTabler/components/ui/inline-player.html.twig')]
final class InlinePlayerComponent
{
    public ?string $id = null;
    public ?string $embedId = null;
    public ?string $type = 'youtube';
}
