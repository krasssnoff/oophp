<?php

declare(strict_types=1);

namespace Oophp;

final class Encoding
{
    public static function base64Encode(string $string): string
    {
        return base64_encode($string);
    }

    public static function base64Decode(string $string, bool $strict = false): string|false
    {
        return base64_decode($string, $strict);
    }

    public static function bin2hex(string $string): string
    {
        return bin2hex($string);
    }

    public static function hex2bin(string $string): string|false
    {
        return hex2bin($string);
    }

    public static function pack(string $format, mixed ...$values): string
    {
        return pack($format, ...$values);
    }

    public static function unpack(string $format, string $string, int $offset = 0): array|false
    {
        return unpack($format, $string, $offset);
    }

    public static function serialize(mixed $value): string
    {
        return serialize($value);
    }

    public static function unserialize(string $data, array $options = []): mixed
    {
        return unserialize($data, $options);
    }
}
