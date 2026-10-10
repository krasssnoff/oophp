<?php

declare(strict_types=1);

namespace Oophp\Chain;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Oophp\Contracts\Chain;
use Oophp\Internal\DateInput;

final readonly class DateChain implements Chain
{
    public function __construct(
        private DateTimeImmutable $value,
    ) {
    }

    public function timezone(DateTimeZone|string $timezone): self
    {
        return new self($this->value->setTimezone(DateInput::timezone($timezone)));
    }

    public function modify(string $modifier): self
    {
        return new self($this->value->modify($modifier));
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
        return new self($this->value->add(DateInput::interval($interval)));
    }

    public function sub(DateInterval|string $interval): self
    {
        return new self($this->value->sub(DateInput::interval($interval)));
    }

    public function format(string $format): StringChain|MixedChain
    {
        return ValueChain::of($this->value->format($format));
    }

    public function timestamp(): NumberChain
    {
        return ValueChain::of($this->value->getTimestamp());
    }

    public function diff(DateTimeInterface|string|int $target, bool $absolute = false): MixedChain
    {
        return ValueChain::of($this->value->diff(DateInput::dateTime($target), $absolute));
    }

    public function isBefore(DateTimeInterface|string|int $target): MixedChain
    {
        return ValueChain::of($this->value < DateInput::dateTime($target));
    }

    public function isAfter(DateTimeInterface|string|int $target): MixedChain
    {
        return ValueChain::of($this->value > DateInput::dateTime($target));
    }

    public function get(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __invoke(): DateTimeImmutable
    {
        return $this->get();
    }
}
