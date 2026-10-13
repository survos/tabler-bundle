<?php

/* src/Twig/Components/Landing/Benefits.php v1.1 - Benefits/process section */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components\Landing;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('landing:benefits', template: '@SurvosTabler/landing/benefits.html.twig')]
final class Benefits
{
    public array $content = [];
    public string $prefix = 'process';
    public string $domain = 'landing';

    public int $columns = 3;
}
