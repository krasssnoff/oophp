<?php

declare(strict_types=1);

namespace Oophp;

final class Type
{
    public static function isArray(mixed $value): bool
    {
        return is_array($value);
    }

    public static function isBool(mixed $value): bool
    {
        return is_bool($value);
    }

    public static function isFloat(mixed $value): bool
    {
        return is_float($value);
    }

    public static function isInt(mixed $value): bool
    {
        return is_int($value);
    }

    public static function isNull(mixed $value): bool
    {
        return is_null($value);
    }

    public static function isNumeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    public static function isObject(mixed $value): bool
    {
        return is_object($value);
    }

    public static function isScalar(mixed $value): bool
    {
        return is_scalar($value);
    }

    public static function isString(mixed $value): bool
    {
        return is_string($value);
    }

    public static function gettype(mixed $value): string
    {
        return gettype($value);
    }

    public static function getDebugType(mixed $value): string
    {
        return get_debug_type($value);
    }

    public static function toInt(mixed $value): int
    {
        return (int) $value;
    }

    public static function toFloat(mixed $value): float
    {
        return (float) $value;
    }

    public static function toString(mixed $value): string
    {
        return (string) $value;
    }

    public static function toBool(mixed $value): bool
    {
        return (bool) $value;
    }
}
