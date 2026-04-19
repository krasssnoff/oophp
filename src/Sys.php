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
}
