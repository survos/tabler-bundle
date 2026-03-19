<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Dto;

final readonly class MenuBadge
{
    public function __construct(
        public string|int $value,
        public ?string $color = null,
    ) {
    }

    public static function fromMixed(mixed $badge): ?self
    {
        if ($badge === null || $badge === false || $badge === '') {
            return null;
        }

        if ($badge instanceof self) {
            return $badge;
        }

        if (is_array($badge)) {
            if (!array_key_exists('value', $badge)) {
                return null;
            }

            return new self(
                $badge['value'],
                isset($badge['color']) && is_string($badge['color']) ? $badge['color'] : null,
            );
        }

        if (is_string($badge) || is_int($badge)) {
            return new self($badge);
        }

        return null;
    }
}
