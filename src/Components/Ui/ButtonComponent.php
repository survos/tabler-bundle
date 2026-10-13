<?php

/* src/Components/Ui/ButtonComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:button', template: '@SurvosTabler/components/ui/button.html.twig')]
final class ButtonComponent
{
    public ?string $color = null;
    public ?string $provider = 'fe';
    public ?string $text = 'Button';
    public ?string $href = null;
    public ?string $external = null;
    public ?string $element = 'a';
    public ?string $type = null;
    public ?string $id = null;
    public ?int $height = null;
    public ?bool $outline = null;
    public ?bool $ghost = null;
    public ?bool $disabled = null;
    public ?bool $square = null;
    public ?string $loading = null;
    public ?bool $pill = null;
    public ?string $class = null;
    public ?string $block = null;
    public ?string $link = null;
    public ?string $iconOnly = null;
    public ?string $modalId = null;
    public ?string $toastId = null;
    public ?string $dismiss = null;
    public ?string $spinner = null;
    public ?string $icon = null;
    public ?string $iconColor = null;
    public ?string $dots = null;
    public ?string $iconEnd = null;
}
