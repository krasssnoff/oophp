<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Time;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TimeTest extends TestCase
{
    public function testTimeDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Time::class, 'of'));
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
            'date' => [date('Y-m-d', $timestamp), Time::date('Y-m-d', $timestamp)],
            'gmdate' => [gmdate('Y-m-d H:i:s', $timestamp), Time::gmdate('Y-m-d H:i:s', $timestamp)],
            'strtotime' => [strtotime('+2 days', $timestamp), Time::strtotime('+2 days', $timestamp)],
            'mktime' => [mktime(12, 30, 15, 5, 10, 2024), Time::mktime(12, 30, 15, 5, 10, 2024)],
        ];
    }

    public function testMicrotimeAndHrtimeShapesMatchNativePhp(): void
    {
        self::assertIsFloat(Time::microtime(true));

        $nativeHrtime = hrtime();
        $wrappedHrtime = Time::hrtime();

        self::assertIsArray($wrappedHrtime);
        self::assertCount(count($nativeHrtime), $wrappedHrtime);
    }

    public function testTimezoneGetAndSetMatchNativePhp(): void
    {
        $original = date_default_timezone_get();

        try {
            self::assertSame(date_default_timezone_set('UTC'), Time::timezoneSet('UTC'));
            self::assertSame(date_default_timezone_get(), Time::timezoneGet());
        } finally {
            date_default_timezone_set($original);
        }
    }
}
