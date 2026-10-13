<?php

/* src/Components/Ui/InputIconComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:input-icon', template: '@SurvosTabler/components/ui/input-icon.html.twig')]
final class InputIconComponent
{
    public ?string $loader = null;
    public ?string $iconClass = null;
    public ?string $icon = 'search';
    public ?string $class = null;
    public ?string $prepend = null;
    public ?string $type = 'text';
    public ?string $value = null;
    public ?bool $light = null;
    public ?string $rounded = null;
    public ?string $inputClass = null;
    public ?string $placeholder = 'Search…';
    public ?string $readonly = null;
}
