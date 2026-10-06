<?php

declare(strict_types=1);

namespace Oophp;

final class Sys
{
    public static function iniGet(string $option): string|false
    {
        return ini_get($option);
    }

    public static function iniGetAll(?string $extension = null, bool $details = true): array|false
    {
        return ini_get_all($extension, $details);
    }

    public static function extensionLoaded(string $extension): bool
    {
        return extension_loaded($extension);
    }
}
