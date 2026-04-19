<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Arr;
use PHPUnit\Framework\TestCase;

final class ArrTest extends TestCase
{
    public function testStaticValuesMatchesNativePhp(): void
    {
        $input = ['first' => 'a', 'second' => 'b'];

        self::assertSame(array_values($input), Arr::values($input));
    }

    public function testFluentSearchMatchesNativePhp(): void
    {
        $input = ['first' => 'a', 'second' => 'b'];

        $expected = array_search('b', array_values($input), false);
        $actual = Arr::of($input)->values()->search('b')->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentFilterPreservesNativeBehavior(): void
    {
        $input = [0, 1, 2, 3, 4];

        $expected = array_filter($input, static fn (int $value): bool => $value % 2 === 0);
        $actual = Arr::of($input)->filter(static fn (int $value): bool => $value % 2 === 0)->get();

        self::assertSame($expected, $actual);
    }
}
