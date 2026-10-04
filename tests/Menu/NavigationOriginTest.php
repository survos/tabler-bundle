<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Tests\Menu;

use PHPUnit\Framework\TestCase;
use Survos\TablerBundle\Service\NavigationOrigin;

final class NavigationOriginTest extends TestCase
{
    public function testARoundTripKeepsThePathAndQuery(): void
    {
        $path = '/search/content?article%5Bquery%5D=flood&p=2';
        self::assertSame($path, NavigationOrigin::decode((string) NavigationOrigin::encode($path)));
        // The token the search controller builds in the browser for /search/content?article%5Bquery%5D=flood
        self::assertSame('/search/content?article%5Bquery%5D=flood', NavigationOrigin::decode('L3NlYXJjaC9jb250ZW50P2FydGljbGUlNUJxdWVyeSU1RD1mbG9vZA'));
    }

    public function testNothingOffTheSiteSurvives(): void
    {
        foreach (['//evil.example/x', 'https://evil.example/', '/a\\b', "/a\nb", '', str_repeat('/a', 400)] as $bad) {
            self::assertNull(NavigationOrigin::encode($bad), $bad);
        }
        self::assertNull(NavigationOrigin::decode(rtrim(strtr(base64_encode('//evil.example'), '+/', '-_'), '=')));
        self::assertNull(NavigationOrigin::decode('not base64 !!'));
    }
}
