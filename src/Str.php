<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\ValueChain;

final class Str
{
    public static function of(mixed $value): ValueChain
    {
        return ValueChain::of($value);
    }

    public static function replace(array|string $search, array|string $replace, string|array $subject): string|array
    {
        return str_replace($search, $replace, $subject);
    }

    public static function lower(string $string): string
    {
        return strtolower($string);
    }

    public static function upper(string $string): string
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

    public static function split(string $separator, string $string, int $limit = PHP_INT_MAX): array
    {
        return explode($separator, $string, $limit);
    }
}
