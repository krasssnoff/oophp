<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\Contracts\Chain;

final readonly class FsPathChain implements Chain
{
    public function __construct(
        private string $path,
    ) {
    }

    public function normalize(): self
    {
        return new self(str_replace('\\', '/', $this->path));
    }

    public function basename(string $suffix = ''): StringChain|MixedChain
    {
        return ValueChain::of(basename($this->path, $suffix));
    }

    public function dirname(int $levels = 1): self
    {
        return new self(dirname($this->path, $levels));
    }

    public function pathinfo(int $flags = PATHINFO_ALL): StringChain|ArrayChain|MixedChain
    {
        return ValueChain::of(pathinfo($this->path, $flags));
    }

    public function realpath(): StringChain|MixedChain
    {
        return ValueChain::of(realpath($this->path));
    }

    public function exists(): MixedChain
    {
        return ValueChain::of(file_exists($this->path));
    }

    public function read(bool $useIncludePath = false, mixed $context = null, int $offset = 0, ?int $length = null): StringChain|MixedChain
    {
        return ValueChain::of(file_get_contents($this->path, $useIncludePath, $context, $offset, $length));
    }

    public function write(mixed $data, int $flags = 0, mixed $context = null): MixedChain
    {
        return ValueChain::of(file_put_contents($this->path, $data, $flags, $context));
    }

    public function copyTo(string $to, mixed $context = null): FsPathChain|MixedChain
    {
        if (!copy($this->path, $to, $context)) {
            return ValueChain::of(false);
        }

        return new self($to);
    }

    public function renameTo(string $to, mixed $context = null): FsPathChain|MixedChain
    {
        if (!rename($this->path, $to, $context)) {
            return ValueChain::of(false);
        }

        return new self($to);
    }

    public function delete(mixed $context = null): MixedChain
    {
        return ValueChain::of(unlink($this->path, $context));
    }

    public function stream(string $mode, bool $useIncludePath = false, mixed $context = null): StreamHandleChain|MixedChain
    {
        $resource = fopen($this->path, $mode, $useIncludePath, $context);
        if ($resource === false) {
            return ValueChain::of(false);
        }

        return new StreamHandleChain($resource);
    }

    public function get(): string
    {
        return $this->path;
    }

    public function __invoke(): string
    {
        return $this->get();
    }
}
