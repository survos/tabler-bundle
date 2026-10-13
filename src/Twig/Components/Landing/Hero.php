<?php

/* src/Twig/Components/Landing/Hero.php v1.1 - Hero section component */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components\Landing;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('landing:hero', template: '@SurvosTabler/landing/hero.html.twig')]
final class Hero
{
    public array $content = [];
    public string $prefix = 'hero';
    public string $domain = 'landing';

    /** @var array{route: string, label: string, variant?: string, icon?: string}[] */
    public array $buttons = [];

    public ?string $backgroundImage = null;
    public string $highlightClass = 'text-success';
}
