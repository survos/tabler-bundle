<?php

/* src/Components/Ui/EmptyComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:empty', template: '@SurvosTabler/components/ui/empty.html.twig')]
final class EmptyComponent
{
    public ?string $icon = 'mood-sad';
    public ?string $bordered = null;
    public ?string $class = null;
    public ?string $illustration = 'boy-girl.svg';
    public ?string $iconText = null;
    public ?string $title = 'No';
    public ?string $subtitle = 'Try';
    public ?string $buttonText = 'Search';
    public ?string $buttonIcon = 'search';
}
