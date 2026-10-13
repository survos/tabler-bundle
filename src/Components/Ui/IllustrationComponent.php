<?php

/* src/Components/Ui/IllustrationComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:illustration', template: '@SurvosTabler/components/ui/illustration.html.twig')]
final class IllustrationComponent
{
    public ?string $image = null;
    public ?int $height = 128;
    public ?string $class = null;
}
