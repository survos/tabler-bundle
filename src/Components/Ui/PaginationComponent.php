<?php

/* src/Components/Ui/PaginationComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:pagination', template: '@SurvosTabler/components/ui/pagination.html.twig')]
final class PaginationComponent
{
    public ?int $count = 5;
    public ?int $offset = null;
    public ?int $activeItem = 3;
    public ?string $class = null;
    public ?string $firstLast = null;
    public ?string $text = null;
    public ?string $prevDescription = null;
    public ?string $nextDescription = null;
}
