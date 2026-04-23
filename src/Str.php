<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Value\StringChain;

final class Str
{
    public static function of(string $value): StringChain
    {
        return new StringChain($value);
    }

    public static function replace(array|string $search, array|string $replace, string|array $subject): string|array
    {
        return str_replace($search, $replace, $subject);
    }

    public static function tolower(string $string): string
    {
        return strtolower($string);
    }

    public static function toupper(string $string): string
    {
        return strtoupper($string);
    }

    public static function trim(string $string, string $characters = " \n\r\t\v\x00"): string
    {
        return trim($string, $characters);
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    public static function len(string $string): int
    {
        return strlen($string);
    }

    public static function pos(string $haystack, string $needle, int $offset = 0): int|false
    {
        return strpos($haystack, $needle, $offset);
    }

    public static function ipos(string $haystack, string $needle, int $offset = 0): int|false
    {
        return stripos($haystack, $needle, $offset);
    }

    public static function rpos(string $haystack, string $needle, int $offset = 0): int|false
    {
        return strrpos($haystack, $needle, $offset);
    }

    public static function ripos(string $haystack, string $needle, int $offset = 0): int|false
    {
        return strripos($haystack, $needle, $offset);
    }

    public static function repeat(string $string, int $times): string
    {
        return str_repeat($string, $times);
    }

    public static function rev(string $string): string
    {
        return strrev($string);
    }

    public static function substr(string $string, int $offset, ?int $length = null): string
    {
        return substr($string, $offset, $length);
    }

    public static function substrCount(string $haystack, string $needle, int $offset = 0, ?int $length = null): int
    {
        return substr_count($haystack, $needle, $offset, $length);
    }

    public static function substrReplace(
        string|array $string,
        string|array $replace,
        int|array $offset,
        int|array|null $length = null,
    ): string|array {
        return substr_replace($string, $replace, $offset, $length);
    }

    public static function split(string $separator, string $string, int $limit = PHP_INT_MAX): array
    {
        return explode($separator, $string, $limit);
    }
}
