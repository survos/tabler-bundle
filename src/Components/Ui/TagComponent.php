<?php

/* src/Components/Ui/TagComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:tag', template: '@SurvosTabler/components/ui/tag.html.twig')]
final class TagComponent
{
    public ?string $flag = null;
    public ?string $icon = null;
    public ?array $person = null;
    public ?string $personId = null;
    public ?string $payment = null;
    public ?string $status = null;
    public ?string $legend = null;
    public ?bool $checkbox = null;
    public ?string $checked = null;
    public ?string $text = null;
    public ?string $badge = null;
}
