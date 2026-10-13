<?php

/* src/Components/Ui/PaymentComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:payment', template: '@SurvosTabler/components/ui/payment.html.twig')]
final class PaymentComponent
{
    public ?int $size = null;
    public ?string $payment = 'visa';
    public ?bool $dark = null;
    public ?string $class = null;
}
