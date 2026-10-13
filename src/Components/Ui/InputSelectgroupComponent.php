<?php

/* src/Components/Ui/InputSelectgroupComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:input-selectgroup', template: '@SurvosTabler/components/ui/input-selectgroup.html.twig')]
final class InputSelectgroupComponent
{
    public ?string $values = 'One,Two,Three';
    public ?string $default = 'values[0]';
    public ?string $type = 'checkbox';
    public ?string $name = 'name';
    public ?string $class = null;
    public ?string $withText = null;
}
