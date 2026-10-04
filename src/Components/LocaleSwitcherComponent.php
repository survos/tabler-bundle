<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Components;

use Survos\TablerBundle\Service\LocaleLinks;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * A language dropdown you can place anywhere: <twig:tabler:locale-switcher />.
 * The top navbar already gets one through the NAVBAR_LANGUAGE slot (see LocaleMenuSubscriber), so this is
 * for other places (a footer, a landing page); the URL rules are shared through LocaleLinks.
 */
#[AsTwigComponent(name: 'tabler:locale-switcher', template: '@SurvosTabler/components/locale-switcher.html.twig')]
final class LocaleSwitcherComponent
{
    public string $variant = 'ghost-secondary';
    public string $size = '';
    public bool $showFlag = false;
    public bool $showFullName = true;

    public function __construct(private readonly LocaleLinks $links) {}

    #[ExposeInTemplate]
    public function getCurrentLocale(): string
    {
        return $this->links->current();
    }

    #[ExposeInTemplate]
    public function getLocales(): array
    {
        return $this->links->all();
    }

    #[ExposeInTemplate]
    public function hasMultipleLocales(): bool
    {
        return $this->links->hasMultiple();
    }
}
