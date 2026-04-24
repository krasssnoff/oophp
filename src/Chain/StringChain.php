<?php

declare(strict_types=1);

namespace Oophp\Chain;

readonly class StringChain extends MixedChain
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function replace(array|string $search, array|string $replace): StringChain
    {
        return self::wrap(str_replace($search, $replace, $this->value));
    }

    public function tolower(): StringChain
    {
        return self::wrap(strtolower($this->value));
    }

    public function toupper(): StringChain
    {
        return self::wrap(strtoupper($this->value));
    }

    public function trim(string $characters = " \n\r\t\v\x00"): StringChain
    {
        return self::wrap(trim($this->value, $characters));
    }

    public function contains(string $needle): MixedChain
    {
        return self::wrap(str_contains($this->value, $needle));
    }

    public function startsWith(string $needle): MixedChain
    {
        return self::wrap(str_starts_with($this->value, $needle));
    }

    public function endsWith(string $needle): MixedChain
    {
        return self::wrap(str_ends_with($this->value, $needle));
    }

    public function len(): MixedChain
    {
        return self::wrap(strlen($this->value));
    }

    public function pos(string $needle, int $offset = 0): MixedChain
    {
        return self::wrap(strpos($this->value, $needle, $offset));
    }

    public function ipos(string $needle, int $offset = 0): MixedChain
    {
        return self::wrap(stripos($this->value, $needle, $offset));
    }

    public function rpos(string $needle, int $offset = 0): MixedChain
    {
        return self::wrap(strrpos($this->value, $needle, $offset));
    }

    public function ripos(string $needle, int $offset = 0): MixedChain
    {
        return self::wrap(strripos($this->value, $needle, $offset));
    }

    public function repeat(int $times): StringChain
    {
        return self::wrap(str_repeat($this->value, $times));
    }

    public function rev(): StringChain
    {
        return self::wrap(strrev($this->value));
    }

    public function substr(int $offset, ?int $length = null): StringChain
    {
        return self::wrap(substr($this->value, $offset, $length));
    }

    public function substrCount(string $needle, int $offset = 0, ?int $length = null): MixedChain
    {
        return self::wrap(substr_count($this->value, $needle, $offset, $length));
    }

    public function substrReplace(string $replace, int $offset, ?int $length = null): StringChain
    {
        return self::wrap(substr_replace($this->value, $replace, $offset, $length));
    }

    public function split(string $separator, int $limit = PHP_INT_MAX): ArrayChain
    {
        return self::wrap(explode($separator, $this->value, $limit));
    }
}
