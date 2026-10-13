<?php

/* src/Components/Ui/TextareaAutosizeComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:textarea-autosize', template: '@SurvosTabler/components/ui/textarea-autosize.html.twig')]
final class TextareaAutosizeComponent
{
    public ?string $class = null;
    public ?string $placeholder = 'Type';
    public ?int $rows = null;
}
