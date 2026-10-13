<?php

/* src/Components/Layout/NavbarSideUserComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Layout;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'layout:navbar-side-user', template: '@SurvosTabler/components/layout/navbar-side-user.html.twig')]
final class NavbarSideUserComponent
{
    public ?int $personId = 1;
    public ?string $hideUsername = null;
    public ?bool $dark = null;
}
