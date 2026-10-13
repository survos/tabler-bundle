<?php

/* src/Components/Layout/NavbarSideComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'layout:navbar-side', template: '@SurvosTabler/components/layout/navbar-side.html.twig')]
final class NavbarSideComponent
{
    public ?int $personId = 1;
    public ?string $class = null;
    public ?string $condensed = null;
    public ?string $breakpoint = null;
    public ?string $showThemeToggle = null;
    public ?string $showNotifications = null;
    public ?string $showApps = null;
    public ?string $showLanguageSelector = null;
    public ?string $showUser = null;
    public ?string $hideUsername = null;
    public ?bool $dark = null;
}
