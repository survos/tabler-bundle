<?php

/* src/Components/Ui/NavComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:nav', template: '@SurvosTabler/components/ui/nav.html.twig')]
final class NavComponent
{
    public ?string $pills = null;
    public ?string $header = null;
    public ?string $tabs = null;
    public ?string $class = null;
}
