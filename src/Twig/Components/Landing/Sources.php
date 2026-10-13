<?php

/* src/Twig/Components/Landing/Sources.php v1.1 - Data sources section */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components\Landing;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('landing:sources', template: '@SurvosTabler/landing/sources.html.twig')]
final class Sources
{
    public array $content = [];
    public string $prefix = 'sources';
    public string $domain = 'landing';

    public bool $darkTheme = true;
}
