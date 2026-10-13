<?php

/* src/Components/Ui/FlagComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:flag', template: '@SurvosTabler/components/ui/flag.html.twig')]
final class FlagComponent
{
    public ?int $size = null;
    public ?string $flag = 'pl';
    public ?string $class = null;
}
