<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\NumberChain;

final class Math
{
    public static function of(int|float $value): NumberChain
    {
        return new NumberChain($value);
    }

    public static function abs(int|float $num): int|float
    {
        return abs($num);
    }

    public static function ceil(int|float $num): float
    {
        return ceil($num);
    }

    public static function floor(int|float $num): float
    {
        return floor($num);
    }

    public static function round(int|float $num, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): float
    {
        return round($num, $precision, $mode);
    }

    public static function max(mixed $value, mixed ...$values): mixed
    {
        return max($value, ...$values);
    }

    public static function min(mixed $value, mixed ...$values): mixed
    {
        return min($value, ...$values);
    }

    public static function pow(int|float $num, int|float $exponent): int|float
    {
        return pow($num, $exponent);
    }

    public static function sqrt(int|float $num): float
    {
        return sqrt($num);
    }

    public static function fmod(float $num1, float $num2): float
    {
        return fmod($num1, $num2);
    }

    public static function intdiv(int $num1, int $num2): int
    {
        return intdiv($num1, $num2);
    }
}
