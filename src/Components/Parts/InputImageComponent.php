<?php

/* src/Components/Parts/InputImageComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:input-image', template: '@SurvosTabler/components/parts/input-image.html.twig')]
final class InputImageComponent
{
    public ?int $limit = 6;
    public ?int $offset = 0;
    public ?string $rowClass = 'col-6';
}
