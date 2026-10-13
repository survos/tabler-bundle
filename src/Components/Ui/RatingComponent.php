<?php

/* src/Components/Ui/RatingComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:rating', template: '@SurvosTabler/components/ui/rating.html.twig')]
final class RatingComponent
{
    public ?string $id = null;
    public ?string $icon = 'default';
    public ?string $color = null;
    public ?int $size = null;
    public ?string $value = null;
}
