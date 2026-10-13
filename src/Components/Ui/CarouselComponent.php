<?php

/* src/Components/Ui/CarouselComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:carousel', template: '@SurvosTabler/components/ui/carousel.html.twig')]
final class CarouselComponent
{
    public ?int $limit = 5;
    public ?int $offset = 0;
    public ?string $id = 'carousel';
    public ?string $fade = null;
    public ?string $indicators = null;
    public ?string $indicatorsVertical = null;
    public ?string $indicatorsDot = null;
    public ?string $indicatorsThumb = null;
    public ?string $indicatorsThumbRatio = null;
    public ?string $captions = null;
    public ?string $controls = null;
}
