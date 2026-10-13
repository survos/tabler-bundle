<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'tabler:icon', template: '@SurvosTabler/components/tabler/icon.html.twig')]
final class TablerIcon
{
}
