<?php

/* src/Components/Ui/BrowserComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:browser', template: '@SurvosTabler/components/ui/browser.html.twig')]
final class BrowserComponent
{
    public ?string $url = null;
}
