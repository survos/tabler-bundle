<?php

/* src/Components/Layout/NavbarLogoComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'layout:navbar-logo', template: '@SurvosTabler/components/layout/navbar-logo.html.twig')]
final class NavbarLogoComponent
{
    public ?string $prefix = 'navbar';
    public ?string $breakpoint = 'lg';
    public ?string $class = null;
    public ?string $header = null;
    public ?string $href = null;
    public ?string $hideLogo = null;
    public ?string $smallLogo = null;
    public ?string $showTitle = null;
}
