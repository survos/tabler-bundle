<?php

/* src/Components/Ui/PhotoComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:photo', template: '@SurvosTabler/components/ui/photo.html.twig')]
final class PhotoComponent
{
    public ?string $id = null;
    public ?string $horizontal = null;
    public ?string $background = null;
    public ?string $class = null;
    public ?string $ratio = null;
}
