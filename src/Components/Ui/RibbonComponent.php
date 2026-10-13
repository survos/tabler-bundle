<?php

/* src/Components/Ui/RibbonComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:ribbon', template: '@SurvosTabler/components/ui/ribbon.html.twig')]
final class RibbonComponent
{
    public ?string $top = null;
    public ?string $left = null;
    public ?string $bottom = null;
    public ?string $bookmark = null;
    public ?string $color = null;
    public ?string $text = null;
    public ?string $filled = null;
}
