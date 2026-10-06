<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\Contracts\Chain;
use Oophp\Chain\ArrayChain;
use Oophp\Chain\MixedChain;
use Oophp\Chain\StringChain;

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

    public static function of(mixed $value): ArrayChain|StringChain|MixedChain
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
     * @return ($value is array ? ArrayChain : ($value is string ? StringChain : MixedChain))
     */
    protected static function wrap(mixed $value): ArrayChain|StringChain|MixedChain
    {
        if (is_array($value)) {
            return new ArrayChain($value);
        }

        if (is_string($value)) {
            return new StringChain($value);
        }

        return new MixedChain($value);
    }
}
