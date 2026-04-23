<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\Contracts\Chain;

readonly class ValueChain implements Chain
{
    public function __construct(
        private mixed $value,
    ) {
    }

    public static function of(mixed $value): self
    {
        return new self($value);
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function __invoke(): mixed
    {
        return $this->get();
    }

    public function values(): self
    {
        return new self(array_values($this->value));
    }

    public function keys(): self
    {
        return new self(array_keys($this->value));
    }

    public function search(mixed $needle, bool $strict = false): self
    {
        return new self(array_search($needle, $this->value, $strict));
    }

    public function filter(?callable $callback = null, int $mode = 0): self
    {
        return new self(array_filter($this->value, $callback, $mode));
    }

    public function map(?callable $callback, array ...$arrays): self
    {
        return new self(array_map($callback, $this->value, ...$arrays));
    }

    public function reverse(bool $preserveKeys = false): self
    {
        return new self(array_reverse($this->value, $preserveKeys));
    }

    public function merge(array ...$arrays): self
    {
        return new self(array_merge($this->value, ...$arrays));
    }

    public function slice(int $offset, ?int $length = null, bool $preserveKeys = false): self
    {
        return new self(array_slice($this->value, $offset, $length, $preserveKeys));
    }

    public function unique(int $flags = SORT_STRING): self
    {
        return new self(array_unique($this->value, $flags));
    }

    public function chunk(int $length, bool $preserveKeys = false): self
    {
        return new self(array_chunk($this->value, $length, $preserveKeys));
    }

    public function flip(): self
    {
        return new self(array_flip($this->value));
    }

    public function pad(int $length, mixed $value): self
    {
        return new self(array_pad($this->value, $length, $value));
    }

    public function combine(array $values): self
    {
        return new self(array_combine($this->value, $values));
    }

    public function mergeRecursive(array ...$arrays): self
    {
        return new self(array_merge_recursive($this->value, ...$arrays));
    }

    public function column(int|string|null $columnKey, int|string|null $indexKey = null): self
    {
        return new self(array_column($this->value, $columnKey, $indexKey));
    }

    public function diff(array ...$arrays): self
    {
        return new self(array_diff($this->value, ...$arrays));
    }

    public function intersect(array ...$arrays): self
    {
        return new self(array_intersect($this->value, ...$arrays));
    }

    public function replaceArray(array ...$replacements): self
    {
        return new self(array_replace($this->value, ...$replacements));
    }

    public function countValues(): self
    {
        return new self(array_count_values($this->value));
    }

    public function inArray(mixed $needle, bool $strict = false): self
    {
        return new self(in_array($needle, $this->value, $strict));
    }

    public function isList(): self
    {
        return new self(array_is_list($this->value));
    }

    public function changeKeyCase(int $case = CASE_LOWER): self
    {
        return new self(array_change_key_case($this->value, $case));
    }

    public function fillKeys(mixed $value): self
    {
        return new self(array_fill_keys($this->value, $value));
    }

    public function keyFirst(): self
    {
        return new self(array_key_first($this->value));
    }

    public function keyLast(): self
    {
        return new self(array_key_last($this->value));
    }

    public function diffAssoc(array ...$arrays): self
    {
        return new self(array_diff_assoc($this->value, ...$arrays));
    }

    public function diffKey(array ...$arrays): self
    {
        return new self(array_diff_key($this->value, ...$arrays));
    }

    public function intersectAssoc(array ...$arrays): self
    {
        return new self(array_intersect_assoc($this->value, ...$arrays));
    }

    public function intersectKey(array ...$arrays): self
    {
        return new self(array_intersect_key($this->value, ...$arrays));
    }

    public function replaceRecursive(array ...$replacements): self
    {
        return new self(array_replace_recursive($this->value, ...$replacements));
    }

    public function sum(): self
    {
        return new self(array_sum($this->value));
    }

    public function product(): self
    {
        return new self(array_product($this->value));
    }

    public function keyExists(int|string $key): self
    {
        return new self(array_key_exists($key, $this->value));
    }

    public function replace(array|string $search, array|string $replace): self
    {
        return new self(str_replace($search, $replace, $this->value));
    }

    public function toLower(): self
    {
        return new self(strtolower($this->value));
    }

    public function toUpper(): self
    {
        return new self(strtoupper($this->value));
    }

    public function trim(string $characters = " \n\r\t\v\x00"): self
    {
        return new self(trim($this->value, $characters));
    }

    public function contains(string $needle): self
    {
        return new self(str_contains($this->value, $needle));
    }

    public function split(string $separator, int $limit = PHP_INT_MAX): self
    {
        return new self(explode($separator, $this->value, $limit));
    }

    public function jsonEncode(int $flags = 0, int $depth = 512): self
    {
        return new self(json_encode($this->value, $flags, $depth));
    }

    public function jsonDecode(?bool $associative = true, int $depth = 512, int $flags = 0): self
    {
        return new self(json_decode($this->value, $associative, $depth, $flags));
    }
}
