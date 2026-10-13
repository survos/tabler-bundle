<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name: 'tabler:footer',
    template: '@SurvosTabler/components/tabler/footer.html.twig'
)]
final class TablerFooter
{
    /** Display year; null => current year from Twig */
    public ?int $year = null;

    /** App / site name shown in footer */
    public string $appName = 'My App';

    /**
     * @var array<string,string> Link label => URL
     */
    public array $links = [
        'Documentation' => 'https://example.com/docs',
        'License' => '#',
    ];

    /** Debug flag to render raw props as a pretty JSON block */
    public bool $debug = false;
}
