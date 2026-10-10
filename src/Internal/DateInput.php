<?php

declare(strict_types=1);

namespace Oophp\Internal;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * Normalizes date input for the Date helpers and DateChain.
 *
 * @internal
 */
final class DateInput
{
    private function __construct()
    {
    }

    public static function dateTime(DateTimeInterface|string|int|null $value, DateTimeZone|string|null $timezone = null): DateTimeImmutable
    {
        if ($value instanceof DateTimeImmutable) {
            return $timezone === null ? $value : $value->setTimezone(self::timezone($timezone));
        }

        if ($value instanceof DateTimeInterface) {
            $immutable = DateTimeImmutable::createFromInterface($value);

            return $timezone === null ? $immutable : $immutable->setTimezone(self::timezone($timezone));
        }

        if (is_int($value)) {
            return (new DateTimeImmutable('@' . $value))->setTimezone(self::timezone($timezone));
        }

        return new DateTimeImmutable($value ?? 'now', self::timezone($timezone));
    }

    public static function timezone(DateTimeZone|string|null $timezone): DateTimeZone
    {
        if ($timezone instanceof DateTimeZone) {
            return $timezone;
        }

        return new DateTimeZone($timezone ?? date_default_timezone_get());
    }

    public static function interval(DateInterval|string $interval): DateInterval
    {
        return $interval instanceof DateInterval ? $interval : new DateInterval($interval);
    }
}
