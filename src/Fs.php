<?php

declare(strict_types=1);

namespace Oophp;

final class Fs
{
    public static function fileGetContents(
        string $filename,
        bool $useIncludePath = false,
        mixed $context = null,
        int $offset = 0,
        ?int $length = null,
    ): string|false {
        return file_get_contents($filename, $useIncludePath, $context, $offset, $length);
    }

    public static function filePutContents(
        string $filename,
        mixed $data,
        int $flags = 0,
        mixed $context = null,
    ): int|false {
        return file_put_contents($filename, $data, $flags, $context);
    }

    public static function fileExists(string $filename): bool
    {
        return file_exists($filename);
    }

    public static function glob(string $pattern, int $flags = 0): array|false
    {
        return glob($pattern, $flags);
    }

    public static function scandir(string $directory, int $sortingOrder = SCANDIR_SORT_ASCENDING, mixed $context = null): array|false
    {
        return scandir($directory, $sortingOrder, $context);
    }

    public static function copy(string $from, string $to, mixed $context = null): bool
    {
        return copy($from, $to, $context);
    }

    public static function rename(string $from, string $to, mixed $context = null): bool
    {
        return rename($from, $to, $context);
    }

    public static function unlink(string $filename, mixed $context = null): bool
    {
        return unlink($filename, $context);
    }

    public static function mkdir(string $directory, int $permissions = 0777, bool $recursive = false, mixed $context = null): bool
    {
        return mkdir($directory, $permissions, $recursive, $context);
    }
}
