<?php

/* src/Components/Ui/InputGroupComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:input-group', template: '@SurvosTabler/components/ui/input-group.html.twig')]
final class InputGroupComponent
{
    public ?string $flat = null;
    public ?string $class = null;
    public ?string $prepend = null;
    public ?string $prependHtml = null;
    public ?string $type = 'text';
    public ?string $inputClass = null;
    public ?string $placeholder = null;
    public ?string $value = null;
    public ?string $id = null;
    public ?string $append = null;
    public ?string $appendLink = null;
    public ?string $appendHtml = null;
    public ?string $appendButton = null;
}
