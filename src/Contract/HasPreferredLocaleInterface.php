<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Contract;

/**
 * Implemented by an app's User entity (typically via PreferredLocaleTrait) so
 * LocaleSubscriber can persist the locale chosen via the locale switcher.
 */
interface HasPreferredLocaleInterface
{
    public function getPreferredLocale(): ?string;

    public function setPreferredLocale(?string $preferredLocale): void;
}
