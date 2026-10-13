<?php

/* src/Components/Ui/ToastComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:toast', template: '@SurvosTabler/components/ui/toast.html.twig')]
final class ToastComponent
{
    public ?string $toastId = 'simple';
    public ?int $personId = 2;
    public ?bool $show = null;
    public ?string $hideHeader = null;
    public ?string $date = null;
    public ?string $cookies = null;
    public ?string $text = 'Hello,';
}
