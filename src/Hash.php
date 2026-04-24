<?php

declare(strict_types=1);

namespace Oophp;

final class Hash
{
    public static function hash(string $algo, string $data, bool $binary = false, array $options = []): string|false
    {
        return hash($algo, $data, $binary, $options);
    }

    public static function hashHmac(string $algo, string $data, string $key, bool $binary = false): string|false
    {
        return hash_hmac($algo, $data, $key, $binary);
    }

    public static function hashEquals(string $knownString, string $userString): bool
    {
        return hash_equals($knownString, $userString);
    }

    public static function passwordHash(string $password, string|int|null $algo, array $options = []): string|false|null
    {
        return password_hash($password, $algo, $options);
    }

    public static function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function passwordNeedsRehash(string $hash, string|int|null $algo, array $options = []): bool
    {
        return password_needs_rehash($hash, $algo, $options);
    }

    public static function randomBytes(int $length): string
    {
        return random_bytes($length);
    }

    public static function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    public static function md5(string $string, bool $binary = false): string
    {
        return md5($string, $binary);
    }

    public static function sha1(string $string, bool $binary = false): string
    {
        return sha1($string, $binary);
    }
}
