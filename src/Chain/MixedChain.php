<?php

declare(strict_types=1);

namespace Oophp\Chain;

readonly class MixedChain extends ValueChain
{
    public function jsonEncode(int $flags = 0, int $depth = 512): StringChain|MixedChain
    {
        return self::wrap(json_encode($this->value, $flags, $depth));
    }

    public function jsonDecode(bool $associative = true, int $depth = 512, int $flags = 0): ArrayChain|StringChain|MixedChain
    {
        return self::wrap(json_decode((string) $this->value, $associative, $depth, $flags));
    }
}
