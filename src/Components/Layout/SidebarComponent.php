<?php

/* src/Components/Layout/SidebarComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'layout:sidebar', template: '@SurvosTabler/components/layout/sidebar.html.twig')]
final class SidebarComponent
{
    public ?string $breakpoint = 'lg';
    public ?string $end = null;
    public ?string $transparent = null;
    public ?string $background = null;
    public ?string $class = null;
    public ?string $backgroundColor = null;
    public ?bool $dark = null;
    public ?string $hideBrand = null;
    public ?string $hideUsername = null;
    public ?string $personId = null;
}
