<?php

declare(strict_types=1);

namespace Oophp\Chain;

/**
 * @extends MixedChain<int|float>
 */
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

    /**
     * @param PHP_ROUND_HALF_UP|PHP_ROUND_HALF_DOWN|PHP_ROUND_HALF_EVEN|PHP_ROUND_HALF_ODD $mode
     */
    public function round(int $precision = 0, int $mode = PHP_ROUND_HALF_UP): self
    {
        return new self(round($this->value, $precision, $mode));
    }

    public function max(mixed ...$values): ArrayChain|StringChain|NumberChain|MixedChain
    {
        return self::wrap(max($this->value, ...$values));
    }

    public function min(mixed ...$values): ArrayChain|StringChain|NumberChain|MixedChain
    {
        return self::wrap(min($this->value, ...$values));
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
        return new self(fmod($this->value, $num2));
    }

    public function intDiv(int $num2): self
    {
        return new self(intdiv($this->value, $num2));
    }
}
