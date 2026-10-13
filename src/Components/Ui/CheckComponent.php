<?php

/* src/Components/Ui/CheckComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:check', template: '@SurvosTabler/components/ui/check.html.twig')]
final class CheckComponent
{
    public ?string $type = 'checkbox';
    public ?bool $checked = null;
    public ?bool $disabled = null;
    public ?bool $switch = null;
    public ?string $title = null;
    public ?string $name = null;
    public ?string $inline = null;
    public ?string $class = null;
    public ?string $titleOn = null;
    public ?string $titleOff = null;
}
