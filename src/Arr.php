<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Value\ArrayChain;

final class Arr
{
    public static function of(array $value): ArrayChain
    {
        return new ArrayChain($value);
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

    public static function merge(array $array, array ...$arrays): array
    {
        return array_merge($array, ...$arrays);
    }

    public static function slice(array $array, int $offset, ?int $length = null, bool $preserveKeys = false): array
    {
        return array_slice($array, $offset, $length, $preserveKeys);
    }

    public static function unique(array $array, int $flags = SORT_STRING): array
    {
        return array_unique($array, $flags);
    }

    public static function chunk(array $array, int $length, bool $preserveKeys = false): array
    {
        return array_chunk($array, $length, $preserveKeys);
    }

    public static function flip(array $array): array
    {
        return array_flip($array);
    }

    public static function pad(array $array, int $length, mixed $value): array
    {
        return array_pad($array, $length, $value);
    }

    public static function combine(array $keys, array $values): array
    {
        return array_combine($keys, $values);
    }

    public static function mergeRecursive(array $array, array ...$arrays): array
    {
        return array_merge_recursive($array, ...$arrays);
    }

    public static function column(array $array, int|string|null $columnKey, int|string|null $indexKey = null): array
    {
        return array_column($array, $columnKey, $indexKey);
    }

    public static function diff(array $array, array ...$arrays): array
    {
        return array_diff($array, ...$arrays);
    }

    public static function intersect(array $array, array ...$arrays): array
    {
        return array_intersect($array, ...$arrays);
    }

    public static function replace(array $array, array ...$replacements): array
    {
        return array_replace($array, ...$replacements);
    }

    public static function countValues(array $array): array
    {
        return array_count_values($array);
    }

    public static function inArray(mixed $needle, array $haystack, bool $strict = false): bool
    {
        return in_array($needle, $haystack, $strict);
    }

    public static function isList(array $array): bool
    {
        return array_is_list($array);
    }
}
