<?php

declare(strict_types=1);

namespace Oophp\Chain;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Oophp\Contracts\Chain;

final readonly class DateChain implements Chain
{
    public function __construct(
        private DateTimeImmutable $value,
    ) {
    }

    public function timezone(DateTimeZone|string $timezone): self
    {
        return new self($this->value->setTimezone(self::normalizeTimezone($timezone)));
    }

    public function modify(string $modifier): self
    {
        $updated = $this->value->modify($modifier);
        if ($updated === false) {
            throw new \InvalidArgumentException("Invalid date modifier: {$modifier}");
        }

        return new self($updated);
    }

    public function setDate(int $year, int $month, int $day): self
    {
        return new self($this->value->setDate($year, $month, $day));
    }

    public function setTime(int $hour, int $minute, int $second = 0, int $microsecond = 0): self
    {
        return new self($this->value->setTime($hour, $minute, $second, $microsecond));
    }

    public function startOfDay(): self
    {
        return new self($this->value->setTime(0, 0, 0, 0));
    }

    public function endOfDay(): self
    {
        return new self($this->value->setTime(23, 59, 59, 999999));
    }

    public function add(DateInterval|string $interval): self
    {
        return new self($this->value->add(self::normalizeInterval($interval)));
    }

    public function sub(DateInterval|string $interval): self
    {
        return new self($this->value->sub(self::normalizeInterval($interval)));
    }

    public function format(string $format): StringChain|MixedChain
    {
        return ValueChain::of($this->value->format($format));
    }

    public function timestamp(): MixedChain
    {
        return ValueChain::of($this->value->getTimestamp());
    }

    public function diff(DateTimeInterface|string|int $target, bool $absolute = false): MixedChain
    {
        return ValueChain::of($this->value->diff(self::normalizeDateTime($target), $absolute));
    }

    public function isBefore(DateTimeInterface|string|int $target): MixedChain
    {
        return ValueChain::of($this->value < self::normalizeDateTime($target));
    }

    public function isAfter(DateTimeInterface|string|int $target): MixedChain
    {
        return ValueChain::of($this->value > self::normalizeDateTime($target));
    }

    public function get(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __invoke(): DateTimeImmutable
    {
        return $this->get();
    }

    private static function normalizeDateTime(DateTimeInterface|string|int $value): DateTimeImmutable
    {
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        if (is_int($value)) {
            return (new DateTimeImmutable('@' . $value))->setTimezone(new DateTimeZone(date_default_timezone_get()));
        }

        return new DateTimeImmutable($value);
    }

    private static function normalizeTimezone(DateTimeZone|string $timezone): DateTimeZone
    {
        if ($timezone instanceof DateTimeZone) {
            return $timezone;
        }

        return new DateTimeZone($timezone);
    }

    private static function normalizeInterval(DateInterval|string $interval): DateInterval
    {
        if ($interval instanceof DateInterval) {
            return $interval;
        }

        return new DateInterval($interval);
    }
}
