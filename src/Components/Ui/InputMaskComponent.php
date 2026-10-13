<?php

/* src/Components/Ui/InputMaskComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:input-mask', template: '@SurvosTabler/components/ui/input-mask.html.twig')]
final class InputMaskComponent
{
    public ?string $name = 'mask';
    public ?string $mask = '00/00/0000';
    public ?string $visible = null;
    public ?string $placeholder = null;
    public ?bool $reverse = null;
}
