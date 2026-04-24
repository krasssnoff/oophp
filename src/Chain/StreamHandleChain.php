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

    public function read(int $length): StringChain|MixedChain
    {
        return ValueChain::of(fread($this->handle, $length));
    }

    public function write(string $data, ?int $length = null): MixedChain
    {
        return ValueChain::of(fwrite($this->handle, $data, $length));
    }

    public function contents(?int $length = null, int $offset = -1): StringChain|MixedChain
    {
        return ValueChain::of(stream_get_contents($this->handle, $length, $offset));
    }

    public function close(): MixedChain
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
