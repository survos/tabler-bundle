<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Add to a User entity + `implements HasPreferredLocaleInterface` to let
 * LocaleSubscriber persist the locale chosen via the locale switcher.
 */
trait PreferredLocaleTrait
{
    #[ORM\Column(type: Types::STRING, length: 5, nullable: true)]
    protected ?string $preferredLocale = null;

    public function getPreferredLocale(): ?string
    {
        return $this->preferredLocale;
    }

    public function setPreferredLocale(?string $preferredLocale): void
    {
        $this->preferredLocale = $preferredLocale;
    }
}
