<?php

declare(strict_types=1);

namespace Oophp\Chain;

final readonly class NumberChain extends MixedChain
{
    public function __construct(int|float $value)
    {
        parent::__construct($value);
    }

    public function abs(): self
    {
        return new self(abs($this->value));
    }

    public function ceil(): self
    {
        return new self(ceil($this->value));
    }

    public function floor(): self
    {
        return new self(floor($this->value));
    }

    public function round(int $precision = 0, int $mode = PHP_ROUND_HALF_UP): self
    {
        return new self(round($this->value, $precision, $mode));
    }

    public function max(mixed ...$values): NumberChain|ArrayChain|StringChain|MixedChain
    {
        return self::wrapNumber(max($this->value, ...$values));
    }

    public function min(mixed ...$values): NumberChain|ArrayChain|StringChain|MixedChain
    {
        return self::wrapNumber(min($this->value, ...$values));
    }

    public function pow(int|float $exponent): self
    {
        return new self(pow($this->value, $exponent));
    }

    public function sqrt(): self
    {
        return new self(sqrt($this->value));
    }

    public function fmod(float $num2): self
    {
        return new self(fmod((float) $this->value, $num2));
    }

    public function intdiv(int $num2): self
    {
        return new self(intdiv((int) $this->value, $num2));
    }

    private static function wrapNumber(mixed $value): NumberChain|ArrayChain|StringChain|MixedChain
    {
        if (is_int($value) || is_float($value)) {
            return new self($value);
        }

        return self::wrap($value);
    }
}
