<?php

/* src/Components/Ui/SignatureComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:signature', template: '@SurvosTabler/components/ui/signature.html.twig')]
final class SignatureComponent
{
    public ?string $id = 'default';
    public ?string $class = null;
    public ?string $clear = null;
    public ?int $width = 400;
    public ?int $height = 400;
    public ?string $event = 'DOMContentLoaded';
    public ?string $extraJs = null;
}
