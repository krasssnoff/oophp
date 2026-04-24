<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Path;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase
{
    public function testPathDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Path::class, 'of'));
    }

    #[DataProvider('staticProvider')]
    public function testStaticMethodsMatchNativePhp(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function staticProvider(): array
    {
        $samplePath = '/var/www/app/archive.tar.gz';
        $existingPath = __FILE__;

        return [
            'basename' => [basename($samplePath), Path::basename($samplePath)],
            'basename_with_suffix' => [basename($samplePath, '.gz'), Path::basename($samplePath, '.gz')],
            'dirname' => [dirname($samplePath), Path::dirname($samplePath)],
            'dirname_levels' => [dirname($samplePath, 2), Path::dirname($samplePath, 2)],
            'pathinfo_all' => [pathinfo($samplePath), Path::pathinfo($samplePath)],
            'pathinfo_extension' => [pathinfo($samplePath, PATHINFO_EXTENSION), Path::pathinfo($samplePath, PATHINFO_EXTENSION)],
            'realpath_existing' => [realpath($existingPath), Path::realpath($existingPath)],
        ];
    }

    public function testRealpathMissingPathMatchesNativePhp(): void
    {
        $missing = '/tmp/oophp-not-existing-path-' . uniqid('', true);

        self::assertSame(realpath($missing), Path::realpath($missing));
    }
}
