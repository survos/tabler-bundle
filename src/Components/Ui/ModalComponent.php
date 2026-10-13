<?php

/* src/Components/Ui/ModalComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:modal', template: '@SurvosTabler/components/ui/modal.html.twig')]
final class ModalComponent
{
    public ?string $modalId = 'simple';
    public ?bool $inline = null;
    public ?string $class = null;
    public ?bool $show = null;
    public ?string $style = null;
    public ?string $top = null;
    public ?string $scrollable = null;
}
