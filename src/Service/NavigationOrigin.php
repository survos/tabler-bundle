<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

/**
 * The way back to a list, carried in the URL of the page you opened from it (the Flickr "in this album" idea).
 *
 * The token is the list's own path and query, base64url-encoded: self-contained, so there is nothing to store, nothing
 * to expire and no request before the click, and a bookmark of the item keeps its way back. The same-site rule lives
 * here, once: only a path that starts with a single "/" survives, so the token can never point off the site.
 */
final class NavigationOrigin
{
    public const PARAMETER = 'in';

    /** A search with a dozen facets still fits; anything longer is not worth carrying in a URL. */
    private const MAX_LENGTH = 600;

    public static function encode(string $pathAndQuery): ?string
    {
        return self::safe($pathAndQuery) ? rtrim(strtr(base64_encode($pathAndQuery), '+/', '-_'), '=') : null;
    }

    /** The same-site path inside a token, or null if it is not one. */
    public static function decode(string $token): ?string
    {
        $path = base64_decode(strtr($token, '-_', '+/'), true);

        return is_string($path) && self::safe($path) ? $path : null;
    }

    /** Same-site paths only: no scheme, no protocol-relative //host, no backslash tricks, no control characters. */
    public static function safe(string $value): bool
    {
        return $value !== '' && strlen($value) <= self::MAX_LENGTH
            && $value[0] === '/' && !str_starts_with($value, '//')
            && !str_contains($value, '\\') && !preg_match('/[\x00-\x1f]/', $value);
    }
}
