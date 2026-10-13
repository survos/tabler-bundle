<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'tabler:mini-card', template: '@SurvosTabler/components/MiniCard.html.twig')]
final class MiniCard
{
    public string $msg = '';
    public string $tagline = '';
    public string $icon = '';
    public string $bgColor = 'green';
    public string $textColor = 'white';
}
