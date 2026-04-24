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

    public function intersectAssoc(array ...$arrays): ArrayChain
    {
        return self::wrap(array_intersect_assoc($this->value, ...$arrays));
    }

    public function intersectKey(array ...$arrays): ArrayChain
    {
        return self::wrap(array_intersect_key($this->value, ...$arrays));
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
}
