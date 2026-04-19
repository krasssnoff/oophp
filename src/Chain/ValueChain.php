<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\Contracts\Chain;

final readonly class ValueChain implements Chain
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

    public function replace(array|string $search, array|string $replace): self
    {
        return new self(str_replace($search, $replace, $this->value));
    }

    public function lower(): self
    {
        return new self(strtolower($this->value));
    }

    public function upper(): self
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
