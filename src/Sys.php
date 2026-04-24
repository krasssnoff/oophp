<?php

declare(strict_types=1);

namespace Oophp;

final class Sys
{
    public static function env(string $name, bool $localOnly = false): string|false
    {
        return getenv($name, $localOnly);
    }

    public static function hostname(): string|false
    {
        return gethostname();
    }

    public static function version(?string $extension = null): string|false
    {
        return phpversion($extension);
    }

    public static function sapi(): string
    {
        return php_sapi_name();
    }

    public static function uname(string $mode = 'a'): string
    {
        return php_uname($mode);
    }

    public static function iniGet(string $option): string|false
    {
        return ini_get($option);
    }

    public static function iniGetAll(?string $extension = null, bool $details = true): array|false
    {
        return ini_get_all($extension, $details);
    }

    public static function iniLoadedFile(): string|false
    {
        return php_ini_loaded_file();
    }

    public static function iniScannedFiles(): string|false
    {
        return php_ini_scanned_files();
    }

    public static function extensionLoaded(string $extension): bool
    {
        return extension_loaded($extension);
    }

    public static function loadedExtensions(bool $zendExtensions = false): array
    {
        return get_loaded_extensions($zendExtensions);
    }

    public static function memoryUsage(bool $realUsage = false): int
    {
        return memory_get_usage($realUsage);
    }

    public static function memoryPeakUsage(bool $realUsage = false): int
    {
        return memory_get_peak_usage($realUsage);
    }

    public static function currentWorkingDirectory(): string|false
    {
        return getcwd();
    }

    public static function tempDirectory(): string
    {
        return sys_get_temp_dir();
    }
}
