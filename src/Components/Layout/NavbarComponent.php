<?php

/* src/Components/Layout/NavbarComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'layout:navbar', template: '@SurvosTabler/components/layout/navbar.html.twig')]
final class NavbarComponent
{
    public ?string $breakpoint = 'md';
    public ?bool $condensed = null;
    public ?bool $showTheme = true;
    public ?bool $showNotifications = true;
    public ?bool $showApps = true;
    public ?bool $showLanguage = true;
    public ?bool $showUser = true;
    public ?string $sticky = null;
    public ?string $transparent = null;
    public ?string $background = null;
    public ?string $overlap = null;
    public ?string $class = null;
    public ?string $backgroundColor = null;
    public ?bool $dark = null;
    public ?string $hideBrand = null;
    public ?string $smallLogo = null;
    public ?string $hideLogo = null;
    public ?string $showTitle = null;
    public ?string $hideUsername = null;
    public ?string $personId = null;
    public ?string $hideSearch = null;
    public ?string $sample = null;
    public ?string $hideIcons = null;
    public ?string $hideMenu = null;
    public ?string $fluidSearch = null;
    public ?string $darkSecondary = null;
}
