<?php

/* src/Components/Parts/SelectComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:select', template: '@SurvosTabler/components/parts/select.html.twig')]
final class SelectComponent
{
    public ?array $options = null;
    public ?string $label = 'Select';
    public ?string $multiple = null;
}
