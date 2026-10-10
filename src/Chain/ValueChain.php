<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\Contracts\Chain;

/**
 * @template-covariant T
 */
abstract readonly class ValueChain implements Chain
{
    /**
     * @param T $value
     */
    public function __construct(
        protected mixed $value,
    ) {
    }

    /**
     * @return ($value is array ? ArrayChain : ($value is string ? StringChain : ($value is int|float ? NumberChain : MixedChain)))
     */
    public static function of(mixed $value): ArrayChain|StringChain|NumberChain|MixedChain
    {
        return self::wrap($value);
    }

    /**
     * @return T
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * @return T
     */
    public function __invoke(): mixed
    {
        return $this->get();
    }

    /**
     * @return ($value is array ? ArrayChain : ($value is string ? StringChain : ($value is int|float ? NumberChain : MixedChain)))
     */
    protected static function wrap(mixed $value): ArrayChain|StringChain|NumberChain|MixedChain
    {
        if (is_array($value)) {
            return new ArrayChain($value);
        }

        if (is_string($value)) {
            return new StringChain($value);
        }

        if (is_int($value) || is_float($value)) {
            return new NumberChain($value);
        }

        return new MixedChain($value);
    }
}
