<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Sys;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SysTest extends TestCase
{
    public function testSysDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Sys::class, 'of'));
    }

    #[DataProvider('staticProvider')]
    public function testStaticReadOnlyMethodsMatchNativePhp(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function staticProvider(): array
    {
        return [
            'ini_get' => [ini_get('memory_limit'), Sys::iniGet('memory_limit')],
            'extension_loaded_json' => [extension_loaded('json'), Sys::extensionLoaded('json')],
        ];
    }

    public function testIniGetAllConformanceByKnownOption(): void
    {
        $native = ini_get_all(null, true);
        $wrapped = Sys::iniGetAll(null, true);

        self::assertIsArray($native);
        self::assertIsArray($wrapped);
        self::assertArrayHasKey('memory_limit', $native);
        self::assertArrayHasKey('memory_limit', $wrapped);
        self::assertSame($native['memory_limit'], $wrapped['memory_limit']);
    }
}
