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
            'env' => [getenv('PATH', false), Sys::env('PATH')],
            'hostname' => [gethostname(), Sys::hostname()],
            'version' => [phpversion(), Sys::version()],
            'sapi' => [php_sapi_name(), Sys::sapi()],
            'uname' => [php_uname('a'), Sys::uname('a')],
            'ini_get' => [ini_get('memory_limit'), Sys::iniGet('memory_limit')],
            'ini_loaded_file' => [php_ini_loaded_file(), Sys::iniLoadedFile()],
            'ini_scanned_files' => [php_ini_scanned_files(), Sys::iniScannedFiles()],
            'extension_loaded_json' => [extension_loaded('json'), Sys::extensionLoaded('json')],
            'loaded_extensions' => [get_loaded_extensions(false), Sys::loadedExtensions(false)],
            'cwd' => [getcwd(), Sys::currentWorkingDirectory()],
            'temp_dir' => [sys_get_temp_dir(), Sys::tempDirectory()],
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

    public function testMemoryUsageAndPeakUsageAreComparable(): void
    {
        self::assertIsInt(Sys::memoryUsage());
        self::assertIsInt(Sys::memoryPeakUsage());
        self::assertSame(memory_get_usage(true), Sys::memoryUsage(true));
    }
}
