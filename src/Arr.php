<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\ValueChain;

final class Arr
{
    public static function of(mixed $value): ValueChain
    {
        return ValueChain::of($value);
    }

    public static function values(array $array): array
    {
        return array_values($array);
    }

    public static function keys(array $array): array
    {
        return array_keys($array);
    }

    public static function search(mixed $needle, array $haystack, bool $strict = false): int|string|false
    {
        return array_search($needle, $haystack, $strict);
    }

    public static function filter(array $array, ?callable $callback = null, int $mode = 0): array
    {
        return array_filter($array, $callback, $mode);
    }

    public static function map(?callable $callback, array $array, array ...$arrays): array
    {
        return array_map($callback, $array, ...$arrays);
    }

    public static function reverse(array $array, bool $preserveKeys = false): array
    {
        return array_reverse($array, $preserveKeys);
    }
}
