<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\Contracts\Chain;
use Oophp\Value\ArrayChain;
use Oophp\Value\MixedChain;
use Oophp\Value\StringChain;

abstract readonly class ValueChain implements Chain
{
    public function __construct(
        protected mixed $value,
    ) {
    }

    public static function of(mixed $value): ArrayChain|StringChain|MixedChain
    {
        return self::wrap($value);
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function __invoke(): mixed
    {
        return $this->get();
    }

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
