<?php

/* src/Components/Ui/AvatarComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'ui:avatar', template: '@SurvosTabler/components/ui/avatar.html.twig')]
final class AvatarComponent
{
    public ?string $src = null;
    public ?string $placeholder = null;
    public ?string $personId = null;
    public ?array $person = null;
    public ?bool $link = null;
    public ?string $dropdown = null;
    public ?int $size = null;
    public ?string $thumb = null;
    public ?string $class = null;
    public ?string $shape = null;
    public ?string $color = null;
    public ?string $status = null;
    public ?string $statusText = null;
    public ?string $statusIcon = null;
    public ?string $brand = null;
    public ?string $icon = null;
}
