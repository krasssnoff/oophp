<?php

declare(strict_types=1);

namespace Oophp\Chain;

readonly class MbStringChain extends MixedChain
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function tolower(?string $encoding = null): MbStringChain
    {
        return self::wrapMb(mb_strtolower($this->value, $encoding));
    }

    public function toupper(?string $encoding = null): MbStringChain
    {
        return self::wrapMb(mb_strtoupper($this->value, $encoding));
    }

    public function len(?string $encoding = null): MixedChain
    {
        return self::wrapMb(mb_strlen($this->value, $encoding));
    }

    public function pos(string $needle, int $offset = 0, ?string $encoding = null): MixedChain
    {
        return self::wrapMb(mb_strpos($this->value, $needle, $offset, $encoding));
    }

    public function rpos(string $needle, int $offset = 0, ?string $encoding = null): MixedChain
    {
        return self::wrapMb(mb_strrpos($this->value, $needle, $offset, $encoding));
    }

    public function substr(int $start, ?int $length = null, ?string $encoding = null): MbStringChain
    {
        return self::wrapMb(mb_substr($this->value, $start, $length, $encoding));
    }

    public function split(int $length = 1, ?string $encoding = null): ArrayChain
    {
        return self::wrapMb(mb_str_split($this->value, $length, $encoding));
    }

    public function contains(string $needle, ?string $encoding = null): MixedChain
    {
        return self::wrapMb(mb_strpos($this->value, $needle, 0, $encoding) !== false);
    }

    public function startsWith(string $needle, ?string $encoding = null): MixedChain
    {
        if ($needle === '') {
            return self::wrapMb(true);
        }

        return self::wrapMb(mb_substr($this->value, 0, mb_strlen($needle, $encoding), $encoding) === $needle);
    }

    public function endsWith(string $needle, ?string $encoding = null): MixedChain
    {
        if ($needle === '') {
            return self::wrapMb(true);
        }

        return self::wrapMb(mb_substr($this->value, -mb_strlen($needle, $encoding), null, $encoding) === $needle);
    }

    protected static function wrapMb(mixed $value): ArrayChain|MbStringChain|MixedChain
    {
        if (is_array($value)) {
            return new ArrayChain($value);
        }

        if (is_string($value)) {
            return new MbStringChain($value);
        }

        return new MixedChain($value);
    }
}
