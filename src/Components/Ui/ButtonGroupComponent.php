<?php

/* src/Components/Ui/ButtonGroupComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:button-group', template: '@SurvosTabler/components/ui/button-group.html.twig')]
final class ButtonGroupComponent
{
    public ?iterable $items = null;
    public ?string $icons = null;
    public ?string $id = null;
    public ?string $vertical = null;
    public ?string $fluid = null;
    public ?string $radio = null;
    public ?string $dropdown = null;
}
