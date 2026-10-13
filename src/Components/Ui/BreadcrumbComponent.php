<?php

/* src/Components/Ui/BreadcrumbComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:breadcrumb', template: '@SurvosTabler/components/ui/breadcrumb.html.twig')]
final class BreadcrumbComponent
{
    public ?string $pages = 'Home,Library,Data';
    public ?string $class = null;
    public ?string $separator = null;
    public ?string $homeIcon = null;
}
