<?php

declare(strict_types=1);

namespace Oophp;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Oophp\Chain\DateChain;

final class Date
{
    public static function of(DateTimeInterface|string|int|null $value = 'now', DateTimeZone|string|null $timezone = null): DateChain
    {
        return new DateChain(oophp_date_normalize_datetime($value, $timezone));
    }

    public static function now(DateTimeZone|string|null $timezone = null): DateTimeImmutable
    {
        return oophp_date_normalize_datetime('now', $timezone);
    }

    public static function parse(string $datetime = 'now', DateTimeZone|string|null $timezone = null): DateTimeImmutable
    {
        return oophp_date_normalize_datetime($datetime, $timezone);
    }

    public static function fromTimestamp(int $timestamp, DateTimeZone|string|null $timezone = null): DateTimeImmutable
    {
        return oophp_date_normalize_datetime($timestamp, $timezone);
    }

    public static function create(
        int $year,
        int $month,
        int $day,
        int $hour = 0,
        int $minute = 0,
        int $second = 0,
        int $microsecond = 0,
        DateTimeZone|string|null $timezone = null,
    ): DateTimeImmutable {
        return oophp_date_normalize_datetime('now', $timezone)->setDate($year, $month, $day)->setTime($hour, $minute, $second, $microsecond);
    }

    public static function createFromFormat(string $format, string $datetime, DateTimeZone|string|null $timezone = null): DateTimeImmutable|false
    {
        return DateTimeImmutable::createFromFormat($format, $datetime, oophp_date_normalize_timezone($timezone));
    }

    public static function timezone(DateTimeInterface|string|int|null $value, DateTimeZone|string $timezone): DateTimeImmutable
    {
        return oophp_date_normalize_datetime($value)->setTimezone(oophp_date_normalize_timezone($timezone));
    }

    public static function format(DateTimeInterface|string|int|null $value, string $format): string
    {
        return oophp_date_normalize_datetime($value)->format($format);
    }

    public static function timestamp(DateTimeInterface|string|int|null $value): int
    {
        return oophp_date_normalize_datetime($value)->getTimestamp();
    }

    public static function diff(DateTimeInterface|string|int|null $from, DateTimeInterface|string|int|null $to, bool $absolute = false): DateInterval
    {
        return oophp_date_normalize_datetime($from)->diff(oophp_date_normalize_datetime($to), $absolute);
    }

    public static function startOfDay(DateTimeInterface|string|int|null $value): DateTimeImmutable
    {
        return oophp_date_normalize_datetime($value)->setTime(0, 0, 0, 0);
    }

    public static function endOfDay(DateTimeInterface|string|int|null $value): DateTimeImmutable
    {
        return oophp_date_normalize_datetime($value)->setTime(23, 59, 59, 999999);
    }

    /**
     * @return list<DateTimeImmutable>
     */
    public static function range(
        DateTimeInterface|string|int|null $start,
        DateTimeInterface|string|int|null $end,
        DateInterval|string $step = 'P1D',
        bool $includeEnd = true,
    ): array {
        $current = oophp_date_normalize_datetime($start);
        $endDate = oophp_date_normalize_datetime($end);
        $interval = $step instanceof DateInterval ? $step : new DateInterval($step);
        $isForward = $current <= $endDate;
        $items = [];

        while ($isForward ? $current < $endDate : $current > $endDate) {
            $items[] = $current;
            $current = $isForward ? $current->add($interval) : $current->sub($interval);
        }

        if ($includeEnd && $current == $endDate) {
            $items[] = $current;
        }

        return $items;
    }

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

function oophp_date_normalize_datetime(DateTimeInterface|string|int|null $value, DateTimeZone|string|null $timezone = null): DateTimeImmutable
{
    if ($value instanceof DateTimeImmutable) {
        return $timezone === null ? $value : $value->setTimezone(oophp_date_normalize_timezone($timezone));
    }

    if ($value instanceof DateTimeInterface) {
        $immutable = DateTimeImmutable::createFromInterface($value);

        return $timezone === null ? $immutable : $immutable->setTimezone(oophp_date_normalize_timezone($timezone));
    }

    if (is_int($value)) {
        $immutable = new DateTimeImmutable('@' . $value);

        return $immutable->setTimezone(oophp_date_normalize_timezone($timezone));
    }

    return new DateTimeImmutable($value ?? 'now', oophp_date_normalize_timezone($timezone));
}

function oophp_date_normalize_timezone(DateTimeZone|string|null $timezone): DateTimeZone
{
    if ($timezone instanceof DateTimeZone) {
        return $timezone;
    }

    if (is_string($timezone)) {
        return new DateTimeZone($timezone);
    }

    return new DateTimeZone(date_default_timezone_get());
}
