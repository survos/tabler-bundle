<?php

/* src/Components/Ui/ResponsiveImageComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:responsive-image', template: '@SurvosTabler/components/ui/responsive-image.html.twig')]
final class ResponsiveImageComponent
{
    public ?string $src = null;
    public ?int $width = 507;
    public ?string $alt = null;
    public ?string $class = null;
    public ?int $height = null;
}
