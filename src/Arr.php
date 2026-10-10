<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\ArrayChain;

final class Arr
{
    private function __construct()
    {
    }

    public static function of(array $value): ArrayChain
    {
        return new ArrayChain($value);
    }

    public static function values(array $array): array
    {
        return array_values($array);
    }

    public static function keys(array $array, mixed ...$rest): array
    {
        return array_keys($array, ...$rest);
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

    public static function merge(array ...$arrays): array
    {
        return array_merge(...$arrays);
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

    public static function mergeRecursive(array ...$arrays): array
    {
        return array_merge_recursive(...$arrays);
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

    public static function changeKeyCase(array $array, int $case = CASE_LOWER): array
    {
        return array_change_key_case($array, $case);
    }

    public static function fillKeys(array $keys, mixed $value): array
    {
        return array_fill_keys($keys, $value);
    }

    public static function keyFirst(array $array): int|string|null
    {
        return array_key_first($array);
    }

    public static function keyLast(array $array): int|string|null
    {
        return array_key_last($array);
    }

    public static function diffAssoc(array $array, array ...$arrays): array
    {
        return array_diff_assoc($array, ...$arrays);
    }

    public static function diffKey(array $array, array ...$arrays): array
    {
        return array_diff_key($array, ...$arrays);
    }

    public static function diffUassoc(array $array, mixed ...$rest): array
    {
        return array_diff_uassoc($array, ...$rest);
    }

    public static function diffUkey(array $array, mixed ...$rest): array
    {
        return array_diff_ukey($array, ...$rest);
    }

    public static function intersectAssoc(array $array, array ...$arrays): array
    {
        return array_intersect_assoc($array, ...$arrays);
    }

    public static function intersectKey(array $array, array ...$arrays): array
    {
        return array_intersect_key($array, ...$arrays);
    }

    public static function intersectUassoc(array $array, mixed ...$rest): array
    {
        return array_intersect_uassoc($array, ...$rest);
    }

    public static function intersectUkey(array $array, mixed ...$rest): array
    {
        return array_intersect_ukey($array, ...$rest);
    }

    public static function replaceRecursive(array $array, array ...$replacements): array
    {
        return array_replace_recursive($array, ...$replacements);
    }

    public static function fill(int $startIndex, int $count, mixed $value): array
    {
        return array_fill($startIndex, $count, $value);
    }

    public static function sum(array $array): int|float
    {
        return array_sum($array);
    }

    public static function product(array $array): int|float
    {
        return array_product($array);
    }

    public static function keyExists(mixed $key, array $array): bool
    {
        return array_key_exists($key, $array);
    }

    public static function reduce(array $array, callable $callback, mixed $initial = null): mixed
    {
        return array_reduce($array, $callback, $initial);
    }

    public static function rand(array $array, int $num = 1): int|string|array
    {
        return array_rand($array, $num);
    }

    public static function udiff(array $array, mixed ...$rest): array
    {
        return array_udiff($array, ...$rest);
    }

    public static function udiffAssoc(array $array, mixed ...$rest): array
    {
        return array_udiff_assoc($array, ...$rest);
    }

    public static function udiffUassoc(array $array, mixed ...$rest): array
    {
        return array_udiff_uassoc($array, ...$rest);
    }

    public static function uintersect(array $array, mixed ...$rest): array
    {
        return array_uintersect($array, ...$rest);
    }

    public static function uintersectAssoc(array $array, mixed ...$rest): array
    {
        return array_uintersect_assoc($array, ...$rest);
    }

    public static function uintersectUassoc(array $array, mixed ...$rest): array
    {
        return array_uintersect_uassoc($array, ...$rest);
    }

    public static function pop(array &$array): mixed
    {
        return array_pop($array);
    }

    public static function push(array &$array, mixed ...$values): int
    {
        return array_push($array, ...$values);
    }

    public static function shift(array &$array): mixed
    {
        return array_shift($array);
    }

    public static function unshift(array &$array, mixed ...$values): int
    {
        return array_unshift($array, ...$values);
    }

    public static function splice(array &$array, int $offset, ?int $length = null, mixed $replacement = []): array
    {
        return array_splice($array, $offset, $length, $replacement);
    }

    public static function walk(array|object &$array, callable $callback, mixed $arg = null): true
    {
        return array_walk($array, $callback, $arg);
    }

    public static function walkRecursive(array|object &$array, callable $callback, mixed $arg = null): true
    {
        return array_walk_recursive($array, $callback, $arg);
    }

    public static function sort(array &$array, int $flags = SORT_REGULAR): true
    {
        return sort($array, $flags);
    }

    public static function rsort(array &$array, int $flags = SORT_REGULAR): true
    {
        return rsort($array, $flags);
    }

    public static function asort(array &$array, int $flags = SORT_REGULAR): true
    {
        return asort($array, $flags);
    }

    public static function arsort(array &$array, int $flags = SORT_REGULAR): true
    {
        return arsort($array, $flags);
    }

    public static function ksort(array &$array, int $flags = SORT_REGULAR): true
    {
        return ksort($array, $flags);
    }

    public static function krsort(array &$array, int $flags = SORT_REGULAR): true
    {
        return krsort($array, $flags);
    }

    public static function natSort(array &$array): true
    {
        return natsort($array);
    }

    public static function natCaseSort(array &$array): true
    {
        return natcasesort($array);
    }

    public static function shuffle(array &$array): true
    {
        return shuffle($array);
    }

    public static function usort(array &$array, callable $callback): true
    {
        return usort($array, $callback);
    }

    public static function uasort(array &$array, callable $callback): true
    {
        return uasort($array, $callback);
    }

    public static function uksort(array &$array, callable $callback): true
    {
        return uksort($array, $callback);
    }

    public static function multiSort(array &$array, mixed ...$rest): bool
    {
        return array_multisort($array, ...$rest);
    }

    public static function implode(array|string $separator, array ...$array): string
    {
        return implode($separator, ...$array);
    }
}
