<?php

declare(strict_types=1);

namespace Oophp\Tests;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Oophp\Chain\DateChain;
use Oophp\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    public function testDateDomainExposesFluentEntryPoint(): void
    {
        self::assertTrue(method_exists(Date::class, 'of'));
        self::assertInstanceOf(DateChain::class, Date::of('2024-01-01 00:00:00', 'UTC'));
    }

    #[DataProvider('staticProvider')]
    public function testStaticMethodsMatchNativePhp(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function staticProvider(): array
    {
        $timestamp = 1_704_067_200;

        return [
            'date' => [date('Y-m-d', $timestamp), Date::date('Y-m-d', $timestamp)],
            'gmdate' => [gmdate('Y-m-d H:i:s', $timestamp), Date::gmdate('Y-m-d H:i:s', $timestamp)],
            'strtotime' => [strtotime('+2 days', $timestamp), Date::strtotime('+2 days', $timestamp)],
            'mktime' => [mktime(12, 30, 15, 5, 10, 2024), Date::mktime(12, 30, 15, 5, 10, 2024)],
        ];
    }

    public function testNowParseAndFromTimestampCreateImmutableDates(): void
    {
        self::assertInstanceOf(DateTimeImmutable::class, Date::now('UTC'));
        self::assertSame('UTC', Date::parse('2024-01-01 00:00:00', 'UTC')->getTimezone()->getName());
        self::assertSame(1_704_067_200, Date::fromTimestamp(1_704_067_200, 'UTC')->getTimestamp());
    }

    public function testCreateAndCreateFromFormatMatchExpectedFields(): void
    {
        $created = Date::create(2024, 5, 10, 12, 30, 15, 123456, 'UTC');
        self::assertSame('2024-05-10 12:30:15.123456', $created->format('Y-m-d H:i:s.u'));

        $fromFormat = Date::createFromFormat('Y-m-d H:i:s', '2024-05-10 12:30:15', 'UTC');
        self::assertInstanceOf(DateTimeImmutable::class, $fromFormat);
        self::assertSame('2024-05-10 12:30:15', $fromFormat->format('Y-m-d H:i:s'));
    }

    public function testTimezoneFormatTimestampDiffAndDayBoundaries(): void
    {
        $base = new DateTimeImmutable('2024-01-02 10:20:30', new DateTimeZone('UTC'));

        self::assertSame('Europe/Berlin', Date::timezone($base, 'Europe/Berlin')->getTimezone()->getName());
        self::assertSame('2024-01-02', Date::format($base, 'Y-m-d'));
        self::assertSame($base->getTimestamp(), Date::timestamp($base));
        self::assertSame('00:00:00.000000', Date::startOfDay($base)->format('H:i:s.u'));
        self::assertSame('23:59:59.999999', Date::endOfDay($base)->format('H:i:s.u'));

        $diff = Date::diff('2024-01-01 00:00:00', '2024-01-03 12:00:00');
        self::assertInstanceOf(DateInterval::class, $diff);
        self::assertSame('2', $diff->format('%a'));
    }

    public function testRangeBuildsForwardAndBackwardSequences(): void
    {
        $forward = Date::range('2024-01-01 00:00:00', '2024-01-03 00:00:00');
        self::assertCount(3, $forward);
        self::assertSame('2024-01-03', $forward[2]->format('Y-m-d'));

        $backward = Date::range('2024-01-03 00:00:00', '2024-01-01 00:00:00', 'P1D');
        self::assertCount(3, $backward);
        self::assertSame('2024-01-01', $backward[2]->format('Y-m-d'));
    }

    public function testMicrotimeAndHrtimeShapesMatchNativePhp(): void
    {
        self::assertIsFloat(Date::microtime(true));

        $nativeHrtime = hrtime();
        $wrappedHrtime = Date::hrtime();

        self::assertIsArray($wrappedHrtime);
        self::assertCount(count($nativeHrtime), $wrappedHrtime);
    }

    public function testTimezoneGetAndSetMatchNativePhp(): void
    {
        $original = date_default_timezone_get();

        try {
            self::assertSame(date_default_timezone_set('UTC'), Date::timezoneSet('UTC'));
            self::assertSame(date_default_timezone_get(), Date::timezoneGet());
        } finally {
            date_default_timezone_set($original);
        }
    }
}
