<?php

declare(strict_types=1);

namespace Oophp;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Oophp\Chain\DateChain;
use Oophp\Internal\DateInput;
use ValueError;

final class Date
{
    private function __construct()
    {
    }

    public static function of(DateTimeInterface|string|int|null $value = 'now', DateTimeZone|string|null $timezone = null): DateChain
    {
        return new DateChain(DateInput::dateTime($value, $timezone));
    }

    public static function now(DateTimeZone|string|null $timezone = null): DateTimeImmutable
    {
        return DateInput::dateTime('now', $timezone);
    }

    public static function parse(string $datetime = 'now', DateTimeZone|string|null $timezone = null): DateTimeImmutable
    {
        return DateInput::dateTime($datetime, $timezone);
    }

    public static function fromTimestamp(int $timestamp, DateTimeZone|string|null $timezone = null): DateTimeImmutable
    {
        return DateInput::dateTime($timestamp, $timezone);
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
        return DateInput::dateTime('now', $timezone)->setDate($year, $month, $day)->setTime($hour, $minute, $second, $microsecond);
    }

    public static function createFromFormat(string $format, string $datetime, DateTimeZone|string|null $timezone = null): DateTimeImmutable|false
    {
        return DateTimeImmutable::createFromFormat($format, $datetime, DateInput::timezone($timezone));
    }

    public static function timezone(DateTimeInterface|string|int|null $value, DateTimeZone|string $timezone): DateTimeImmutable
    {
        return DateInput::dateTime($value)->setTimezone(DateInput::timezone($timezone));
    }

    public static function format(DateTimeInterface|string|int|null $value, string $format): string
    {
        return DateInput::dateTime($value)->format($format);
    }

    public static function timestamp(DateTimeInterface|string|int|null $value): int
    {
        return DateInput::dateTime($value)->getTimestamp();
    }

    public static function diff(DateTimeInterface|string|int|null $from, DateTimeInterface|string|int|null $to, bool $absolute = false): DateInterval
    {
        return DateInput::dateTime($from)->diff(DateInput::dateTime($to), $absolute);
    }

    public static function startOfDay(DateTimeInterface|string|int|null $value): DateTimeImmutable
    {
        return self::of($value)->startOfDay()->get();
    }

    public static function endOfDay(DateTimeInterface|string|int|null $value): DateTimeImmutable
    {
        return self::of($value)->endOfDay()->get();
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
        $current = DateInput::dateTime($start);
        $endDate = DateInput::dateTime($end);
        $interval = DateInput::interval($step);
        $isForward = $current <= $endDate;
        $items = [];

        while ($isForward ? $current < $endDate : $current > $endDate) {
            $items[] = $current;
            $next = $isForward ? $current->add($interval) : $current->sub($interval);

            if ($isForward ? $next <= $current : $next >= $current) {
                throw new ValueError('Oophp\\Date::range(): Argument #3 ($step) must move from $start towards $end');
            }

            $current = $next;
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

    public static function gmDate(string $format, ?int $timestamp = null): string
    {
        return gmdate($format, $timestamp);
    }

    public static function strToTime(string $datetime, ?int $baseTimestamp = null): int|false
    {
        return strtotime($datetime, $baseTimestamp);
    }

    public static function mkTime(int $hour, ?int $minute = null, ?int $second = null, ?int $month = null, ?int $day = null, ?int $year = null): int|false
    {
        return mktime($hour, $minute, $second, $month, $day, $year);
    }

    public static function microTime(bool $asFloat = false): string|float
    {
        return microtime($asFloat);
    }

    // @phpstan-ignore return.unusedType (native hrtime() signature keeps false)
    public static function hrTime(bool $asNumber = false): array|int|float|false
    {
        return hrtime($asNumber);
    }
}
