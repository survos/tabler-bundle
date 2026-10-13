<?php

/* src/Twig/Components/Landing/Faq.php v1.1 - FAQ accordion section */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components\Landing;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('landing:faq', template: '@SurvosTabler/landing/faq.html.twig')]
final class Faq
{
    public array $content = [];
    public string $prefix = 'faq';
    public string $domain = 'landing';

    /** Layout: 'side-by-side' or 'stacked' */
    public string $layout = 'side-by-side';

    /** Show section icons */
    public bool $showIcons = true;
}
