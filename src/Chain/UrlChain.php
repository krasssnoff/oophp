<?php

declare(strict_types=1);

namespace Oophp\Chain;

final readonly class UrlChain extends StringChain
{
    public function parse(int $component = -1): ArrayChain|self|MixedChain
    {
        return self::wrap(parse_url($this->value, $component));
    }

    public function rawEncode(): self
    {
        return self::wrap(rawurlencode($this->value));
    }

    public function rawDecode(): self
    {
        return self::wrap(rawurldecode($this->value));
    }

    public function encode(): self
    {
        return self::wrap(urlencode($this->value));
    }

    public function decode(): self
    {
        return self::wrap(urldecode($this->value));
    }
}
