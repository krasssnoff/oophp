<?php

declare(strict_types=1);

namespace Oophp;

final class Path
{
    public static function basename(string $path, string $suffix = ''): string
    {
        return basename($path, $suffix);
    }

    public static function dirname(string $path, int $levels = 1): string
    {
        return dirname($path, $levels);
    }

    public static function pathinfo(string $path, int $flags = PATHINFO_ALL): string|array
    {
        return pathinfo($path, $flags);
    }

    public static function realpath(string $path): string|false
    {
        return realpath($path);
    }
}
