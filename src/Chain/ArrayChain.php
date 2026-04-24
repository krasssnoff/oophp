<?php

declare(strict_types=1);

namespace Oophp\Chain;

readonly class ArrayChain extends MixedChain
{
    public function __construct(array $value)
    {
        parent::__construct($value);
    }

    public function values(): ArrayChain
    {
        return self::wrap(array_values($this->value));
    }

    public function keys(): ArrayChain
    {
        return self::wrap(array_keys($this->value));
    }

    public function search(mixed $needle, bool $strict = false): StringChain|MixedChain
    {
        return self::wrap(array_search($needle, $this->value, $strict));
    }

    public function filter(?callable $callback = null, int $mode = 0): ArrayChain
    {
        return self::wrap(array_filter($this->value, $callback, $mode));
    }

    public function map(?callable $callback, array ...$arrays): ArrayChain
    {
        return self::wrap(array_map($callback, $this->value, ...$arrays));
    }

    public function reverse(bool $preserveKeys = false): ArrayChain
    {
        return self::wrap(array_reverse($this->value, $preserveKeys));
    }

    public function merge(array ...$arrays): ArrayChain
    {
        return self::wrap(array_merge($this->value, ...$arrays));
    }

    public function slice(int $offset, ?int $length = null, bool $preserveKeys = false): ArrayChain
    {
        return self::wrap(array_slice($this->value, $offset, $length, $preserveKeys));
    }

    public function unique(int $flags = SORT_STRING): ArrayChain
    {
        return self::wrap(array_unique($this->value, $flags));
    }

    public function chunk(int $length, bool $preserveKeys = false): ArrayChain
    {
        return self::wrap(array_chunk($this->value, $length, $preserveKeys));
    }

    public function flip(): ArrayChain
    {
        return self::wrap(array_flip($this->value));
    }

    public function pad(int $length, mixed $value): ArrayChain
    {
        return self::wrap(array_pad($this->value, $length, $value));
    }

    public function combine(array $values): ArrayChain
    {
        return self::wrap(array_combine($this->value, $values));
    }

    public function mergeRecursive(array ...$arrays): ArrayChain
    {
        return self::wrap(array_merge_recursive($this->value, ...$arrays));
    }

    public function column(int|string|null $columnKey, int|string|null $indexKey = null): ArrayChain
    {
        return self::wrap(array_column($this->value, $columnKey, $indexKey));
    }

    public function diff(array ...$arrays): ArrayChain
    {
        return self::wrap(array_diff($this->value, ...$arrays));
    }

    public function intersect(array ...$arrays): ArrayChain
    {
        return self::wrap(array_intersect($this->value, ...$arrays));
    }

    public function replaceArray(array ...$replacements): ArrayChain
    {
        return self::wrap(array_replace($this->value, ...$replacements));
    }

    public function countValues(): ArrayChain
    {
        return self::wrap(array_count_values($this->value));
    }

    public function inArray(mixed $needle, bool $strict = false): MixedChain
    {
        return self::wrap(in_array($needle, $this->value, $strict));
    }

    public function isList(): MixedChain
    {
        return self::wrap(array_is_list($this->value));
    }

    public function changeKeyCase(int $case = CASE_LOWER): ArrayChain
    {
        return self::wrap(array_change_key_case($this->value, $case));
    }

    public function fillKeys(mixed $value): ArrayChain
    {
        return self::wrap(array_fill_keys($this->value, $value));
    }

    public function keyFirst(): StringChain|MixedChain
    {
        return self::wrap(array_key_first($this->value));
    }

    public function keyLast(): StringChain|MixedChain
    {
        return self::wrap(array_key_last($this->value));
    }

    public function diffAssoc(array ...$arrays): ArrayChain
    {
        return self::wrap(array_diff_assoc($this->value, ...$arrays));
    }

    public function diffKey(array ...$arrays): ArrayChain
    {
        return self::wrap(array_diff_key($this->value, ...$arrays));
    }

    public function diffUassoc(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_diff_uassoc($this->value, ...$rest));
    }

    public function diffUkey(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_diff_ukey($this->value, ...$rest));
    }

    public function intersectAssoc(array ...$arrays): ArrayChain
    {
        return self::wrap(array_intersect_assoc($this->value, ...$arrays));
    }

    public function intersectKey(array ...$arrays): ArrayChain
    {
        return self::wrap(array_intersect_key($this->value, ...$arrays));
    }

    public function intersectUassoc(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_intersect_uassoc($this->value, ...$rest));
    }

    public function intersectUkey(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_intersect_ukey($this->value, ...$rest));
    }

    public function replaceRecursive(array ...$replacements): ArrayChain
    {
        return self::wrap(array_replace_recursive($this->value, ...$replacements));
    }

    public function sum(): MixedChain
    {
        return self::wrap(array_sum($this->value));
    }

    public function product(): MixedChain
    {
        return self::wrap(array_product($this->value));
    }

    public function keyExists(int|string $key): MixedChain
    {
        return self::wrap(array_key_exists($key, $this->value));
    }

    public function reduce(callable $callback, mixed $initial = null): MixedChain
    {
        return self::wrap(array_reduce($this->value, $callback, $initial));
    }

    public function rand(int $num = 1): ArrayChain|StringChain|MixedChain
    {
        return self::wrap(array_rand($this->value, $num));
    }

    public function udiff(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_udiff($this->value, ...$rest));
    }

    public function udiffAssoc(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_udiff_assoc($this->value, ...$rest));
    }

    public function udiffUassoc(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_udiff_uassoc($this->value, ...$rest));
    }

    public function uintersect(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_uintersect($this->value, ...$rest));
    }

    public function uintersectAssoc(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_uintersect_assoc($this->value, ...$rest));
    }

    public function uintersectUassoc(mixed ...$rest): ArrayChain
    {
        return self::wrap(array_uintersect_uassoc($this->value, ...$rest));
    }

    public function pop(): ArrayChain|StringChain|MixedChain
    {
        $array = $this->value;

        return self::wrap(array_pop($array));
    }

    public function push(mixed ...$values): ArrayChain
    {
        $array = $this->value;
        array_push($array, ...$values);

        return self::wrap($array);
    }

    public function shift(): ArrayChain|StringChain|MixedChain
    {
        $array = $this->value;

        return self::wrap(array_shift($array));
    }

    public function unshift(mixed ...$values): ArrayChain
    {
        $array = $this->value;
        array_unshift($array, ...$values);

        return self::wrap($array);
    }

    public function splice(int $offset, ?int $length = null, array $replacement = []): ArrayChain
    {
        $array = $this->value;
        array_splice($array, $offset, $length, $replacement);

        return self::wrap($array);
    }

    public function walk(callable $callback, mixed $arg = null): ArrayChain
    {
        $array = $this->value;
        array_walk($array, $callback, $arg);

        return self::wrap($array);
    }

    public function walkRecursive(callable $callback, mixed $arg = null): ArrayChain
    {
        $array = $this->value;
        array_walk_recursive($array, $callback, $arg);

        return self::wrap($array);
    }

    public function sort(int $flags = SORT_REGULAR): ArrayChain
    {
        $sorted = $this->value;
        sort($sorted, $flags);

        return self::wrap($sorted);
    }

    public function rsort(int $flags = SORT_REGULAR): ArrayChain
    {
        $sorted = $this->value;
        rsort($sorted, $flags);

        return self::wrap($sorted);
    }

    public function asort(int $flags = SORT_REGULAR): ArrayChain
    {
        $sorted = $this->value;
        asort($sorted, $flags);

        return self::wrap($sorted);
    }

    public function arsort(int $flags = SORT_REGULAR): ArrayChain
    {
        $sorted = $this->value;
        arsort($sorted, $flags);

        return self::wrap($sorted);
    }

    public function ksort(int $flags = SORT_REGULAR): ArrayChain
    {
        $sorted = $this->value;
        ksort($sorted, $flags);

        return self::wrap($sorted);
    }

    public function krsort(int $flags = SORT_REGULAR): ArrayChain
    {
        $sorted = $this->value;
        krsort($sorted, $flags);

        return self::wrap($sorted);
    }

    public function natsort(): ArrayChain
    {
        $sorted = $this->value;
        natsort($sorted);

        return self::wrap($sorted);
    }

    public function natcasesort(): ArrayChain
    {
        $sorted = $this->value;
        natcasesort($sorted);

        return self::wrap($sorted);
    }

    public function shuffle(): ArrayChain
    {
        $array = $this->value;
        shuffle($array);

        return self::wrap($array);
    }

    public function usort(callable $callback): ArrayChain
    {
        $sorted = $this->value;
        usort($sorted, $callback);

        return self::wrap($sorted);
    }

    public function uasort(callable $callback): ArrayChain
    {
        $sorted = $this->value;
        uasort($sorted, $callback);

        return self::wrap($sorted);
    }

    public function uksort(callable $callback): ArrayChain
    {
        $sorted = $this->value;
        uksort($sorted, $callback);

        return self::wrap($sorted);
    }

    public function multisort(mixed ...$rest): ArrayChain
    {
        $sorted = $this->value;
        array_multisort($sorted, ...$rest);

        return self::wrap($sorted);
    }

    public function implode(string $separator): StringChain|MixedChain
    {
        return self::wrap(implode($separator, $this->value));
    }

    public function join(string $separator): StringChain|MixedChain
    {
        return self::wrap(implode($separator, $this->value));
    }
}
