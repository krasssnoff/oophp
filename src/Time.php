<?php

declare(strict_types=1);

namespace Oophp;

final class Time
{
    public static function date(string $format, ?int $timestamp = null): string
    {
        return date($format, $timestamp);
    }

    public static function gmdate(string $format, ?int $timestamp = null): string
    {
        return gmdate($format, $timestamp);
    }

    public static function strtotime(string $datetime, ?int $baseTimestamp = null): int|false
    {
        return strtotime($datetime, $baseTimestamp);
    }

    public static function mktime(int $hour, ?int $minute = null, ?int $second = null, ?int $month = null, ?int $day = null, ?int $year = null): int|false
    {
        return mktime($hour, $minute, $second, $month, $day, $year);
    }

    public static function microtime(bool $asFloat = false): string|float
    {
        return microtime($asFloat);
    }

    public static function hrtime(bool $asNumber = false): array|int|float|false
    {
        return hrtime($asNumber);
    }

    public static function timezoneGet(): string
    {
        return date_default_timezone_get();
    }

    public static function timezoneSet(string $timezoneId): bool
    {
        return date_default_timezone_set($timezoneId);
    }
}
