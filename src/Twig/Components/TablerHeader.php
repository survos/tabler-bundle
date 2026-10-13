<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name: 'tabler:header',
    template: '@SurvosTabler/components/tabler/header.html.twig'
)]
final class TablerHeader
{
    /** Main page title */
    public ?string $title = 'Dashboard';

    /** Optional pretitle (small text above title) */
    public ?string $pretitle = 'Overview';

    /** Optional subtitle (small gray text under title) */
    public ?string $subtitle = null;

    /** Use darker theme styling for overlap/dark headers */
    public bool $dark = false;

    /** If true, pretend the header overlaps a hero/banner background */
    public bool $overlap = false;

    /** Right-side action area hint (purely demonstrative) */
    public bool $showActions = true;

    /** Debug flag to render raw props as a pretty JSON block */
    public bool $debug = false;
}
