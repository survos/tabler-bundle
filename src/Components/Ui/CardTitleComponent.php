<?php
/* src/Components/Ui/CardTitleComponent.php v4.8 - Generated 2026-03-17 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:card-title', template: '@SurvosTabler/components/ui/card-title.html.twig')]
final class CardTitleComponent
{
    public ?string $class = null;
    public ?string $text = null;
    public ?string $title = 'Card';
    public ?string $more = null;

}