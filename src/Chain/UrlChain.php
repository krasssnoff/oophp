<?php

declare(strict_types=1);

namespace Oophp\Chain;

final readonly class UrlChain extends StringChain
{
    public function parse(int $component = -1): ArrayChain|StringChain|MixedChain
    {
        return self::wrap(parse_url($this->value, $component));
    }

    public function rawencode(): StringChain
    {
        return self::wrap(rawurlencode($this->value));
    }

    public function rawdecode(): StringChain
    {
        return self::wrap(rawurldecode($this->value));
    }

    public function encode(): StringChain
    {
        return self::wrap(urlencode($this->value));
    }

    public function decode(): StringChain
    {
        return self::wrap(urldecode($this->value));
    }
}
