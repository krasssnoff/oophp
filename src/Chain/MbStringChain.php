<?php

declare(strict_types=1);

namespace Oophp\Chain;

use Oophp\MbStr;

/**
 * @extends MixedChain<string>
 */
final readonly class MbStringChain extends MixedChain
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function toLower(?string $encoding = null): MbStringChain
    {
        return self::wrap(mb_strtolower($this->value, $encoding));
    }

    public function toUpper(?string $encoding = null): MbStringChain
    {
        return self::wrap(mb_strtoupper($this->value, $encoding));
    }

    public function len(?string $encoding = null): NumberChain
    {
        return self::wrap(mb_strlen($this->value, $encoding));
    }

    public function pos(string $needle, int $offset = 0, ?string $encoding = null): NumberChain|MixedChain
    {
        return self::wrap(mb_strpos($this->value, $needle, $offset, $encoding));
    }

    public function rpos(string $needle, int $offset = 0, ?string $encoding = null): NumberChain|MixedChain
    {
        return self::wrap(mb_strrpos($this->value, $needle, $offset, $encoding));
    }

    public function substr(int $start, ?int $length = null, ?string $encoding = null): MbStringChain
    {
        return self::wrap(mb_substr($this->value, $start, $length, $encoding));
    }

    public function split(int $length = 1, ?string $encoding = null): ArrayChain
    {
        return self::wrap(mb_str_split($this->value, $length, $encoding));
    }

    public function contains(string $needle, ?string $encoding = null): MixedChain
    {
        return self::wrap(MbStr::contains($this->value, $needle, $encoding));
    }

    public function startsWith(string $needle, ?string $encoding = null): MixedChain
    {
        return self::wrap(MbStr::startsWith($this->value, $needle, $encoding));
    }

    public function endsWith(string $needle, ?string $encoding = null): MixedChain
    {
        return self::wrap(MbStr::endsWith($this->value, $needle, $encoding));
    }

    public function jsonDecode(?bool $associative = null, int $depth = 512, int $flags = 0): ArrayChain|MbStringChain|NumberChain|MixedChain
    {
        return self::wrap(json_decode($this->value, $associative, $depth, $flags));
    }

    /**
     * @return ($value is string ? MbStringChain : ($value is array ? ArrayChain : ($value is int|float ? NumberChain : MixedChain)))
     */
    protected static function wrap(mixed $value): ArrayChain|StringChain|NumberChain|MixedChain
    {
        if (is_string($value)) {
            return new self($value);
        }

        return parent::wrap($value);
    }
}
