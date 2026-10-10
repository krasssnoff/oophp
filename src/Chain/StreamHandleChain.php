<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\Contracts\Chain;

final readonly class StreamHandleChain implements Chain
{
    public function __construct(
        private mixed $handle,
    ) {
    }

    public function fread(int $length): StringChain|MixedChain
    {
        return ValueChain::of(fread($this->handle, $length));
    }

    public function fwrite(string $data, ?int $length = null): NumberChain|MixedChain
    {
        return ValueChain::of(fwrite($this->handle, $data, $length));
    }

    public function getContents(?int $length = null, int $offset = -1): StringChain|MixedChain
    {
        return ValueChain::of(stream_get_contents($this->handle, $length, $offset));
    }

    public function fclose(): MixedChain
    {
        return ValueChain::of(fclose($this->handle));
    }

    public function get(): mixed
    {
        return $this->handle;
    }

    public function __invoke(): mixed
    {
        return $this->get();
    }
}
