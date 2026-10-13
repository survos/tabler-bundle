<?php

/* src/Twig/TablerAssetExtension.php v1.0 - Resolve Tabler asset URLs */

declare(strict_types=1);

namespace Survos\TablerBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TablerAssetExtension extends AbstractExtension
{
    public function __construct(
        private readonly string $baseUrl,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tabler_asset', [$this, 'asset']),
            new TwigFunction('tabler_avatar', [$this, 'avatar']),
            new TwigFunction('tabler_photo', [$this, 'photo']),
        ];
    }

    /**
     * Resolve any Tabler static asset
     * {{ tabler_asset('avatars/001m.jpg') }}.
     */
    public function asset(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Remove 'static/' prefix if present
        $path = preg_replace('#^static/#', '', $path);

        return rtrim($this->baseUrl, '/').'/'.ltrim($path, '/');
    }

    /**
     * Shortcut for avatars
     * {{ tabler_avatar('001m') }} → .../avatars/001m.jpg.
     */
    public function avatar(string $id): string
    {
        $ext = str_ends_with($id, '.jpg') ? '' : '.jpg';

        return $this->asset('avatars/'.$id.$ext);
    }

    /**
     * Shortcut for photos
     * {{ tabler_photo('city-lights') }}.
     */
    public function photo(string $name): string
    {
        $ext = str_ends_with($name, '.jpg') ? '' : '.jpg';

        return $this->asset('photos/'.$name.$ext);
    }
}
