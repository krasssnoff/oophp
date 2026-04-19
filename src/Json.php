<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\ValueChain;

final class Json
{
    public static function of(mixed $value): ValueChain
    {
        return ValueChain::of($value);
    }

    public static function encode(mixed $value, int $flags = 0, int $depth = 512): string|false
    {
        return json_encode($value, $flags, $depth);
    }

    public static function decode(string $json, ?bool $associative = true, int $depth = 512, int $flags = 0): mixed
    {
        return json_decode($json, $associative, $depth, $flags);
    }

    public static function validate(string $json, int $depth = 512, int $flags = 0): bool
    {
        return json_validate($json, $depth, $flags);
    }

    public static function lastError(): int
    {
        return json_last_error();
    }

    public static function lastErrorMessage(): string
    {
        return json_last_error_msg();
    }
}
