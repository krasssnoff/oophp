<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\MbStringChain;

final class MbStr
{
    public static function of(string $value): MbStringChain
    {
        return new MbStringChain($value);
    }

    public static function tolower(string $string, ?string $encoding = null): string
    {
        return mb_strtolower($string, $encoding);
    }

    public static function toupper(string $string, ?string $encoding = null): string
    {
        return mb_strtoupper($string, $encoding);
    }

    public static function len(string $string, ?string $encoding = null): int
    {
        return mb_strlen($string, $encoding);
    }

    public static function pos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null): int|false
    {
        return mb_strpos($haystack, $needle, $offset, $encoding);
    }

    public static function rpos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null): int|false
    {
        return mb_strrpos($haystack, $needle, $offset, $encoding);
    }

    public static function substr(string $string, int $start, ?int $length = null, ?string $encoding = null): string
    {
        return mb_substr($string, $start, $length, $encoding);
    }

    public static function split(string $string, int $length = 1, ?string $encoding = null): array
    {
        return mb_str_split($string, $length, $encoding);
    }

    public static function contains(string $haystack, string $needle, ?string $encoding = null): bool
    {
        return self::pos($haystack, $needle, 0, $encoding) !== false;
    }

    public static function startsWith(string $haystack, string $needle, ?string $encoding = null): bool
    {
        if ($needle === '') {
            return true;
        }

        return self::substr($haystack, 0, self::len($needle, $encoding), $encoding) === $needle;
    }

    public static function endsWith(string $haystack, string $needle, ?string $encoding = null): bool
    {
        if ($needle === '') {
            return true;
        }

        return self::substr($haystack, -self::len($needle, $encoding), null, $encoding) === $needle;
    }
}
