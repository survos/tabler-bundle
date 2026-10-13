<?php

/* src/Components/Parts/CalendarComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Parts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'parts:calendar', template: '@SurvosTabler/components/parts/calendar.html.twig')]
final class CalendarComponent
{
    public ?string $startDay = null;
    public ?int $today = 8;
    public ?int $days = 30;
    public ?int $rangeStart = 14;
    public ?int $rangeEnd = 21;
    public ?string $class = null;
    public ?string $title = 'March';
}
