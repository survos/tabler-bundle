<?php

/* src/Twig/Components/Landing/Features.php v1.1 - Features grid component */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components\Landing;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('landing:features', template: '@SurvosTabler/landing/features.html.twig')]
final class Features
{
    public array $content = [];
    public string $prefix = 'feature';
    public string $domain = 'landing';

    /** Number of columns on large screens */
    public int $columns = 3;

    public bool $showIcons = true;
}
