<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Arr;
use Oophp\Str;
use Oophp\Chain\ArrayChain;
use Oophp\Chain\MixedChain;
use Oophp\Chain\StringChain;
use PHPUnit\Framework\TestCase;

final class ValueChainTest extends TestCase
{
    public function testLongStringArrayScalarChainMatchesNativePipeline(): void
    {
        $input = '  alpha,,beta,gamma,beta  ';

        $expected = array_search(
            'beta',
            array_values(
                array_unique(
                    array_filter(
                        explode(',', strtolower(trim($input))),
                        static fn (string $value): bool => $value !== '',
                    ),
                    SORT_STRING,
                ),
            ),
            true,
        );

        $actual = Str::of($input)
            ->trim()
            ->tolower()
            ->split(',')
            ->filter(static fn (string $value): bool => $value !== '')
            ->unique()
            ->values()
            ->search('beta', true)
            ->get();

        self::assertSame($expected, $actual);
    }

    public function testLongArrayChainWithInvokeMatchesNativePipeline(): void
    {
        $input = ['first' => 'x', 'second' => 'y', 'third' => 'x', 'fourth' => 'z'];

        $expected = array_search(
            'z',
            array_values(
                array_unique(
                    array_reverse($input, false),
                    SORT_STRING,
                ),
            ),
            false,
        );

        $actual = Arr::of($input)
            ->reverse()
            ->unique()
            ->values()
            ->search('z')();

        self::assertSame($expected, $actual);
    }

    public function testScalarTerminalResultStaysInMixedChainAfterLongChain(): void
    {
        $chain = Arr::of([1, 2, 3, 4])
            ->filter(static fn (int $value): bool => $value > 1)
            ->pad(5, 0)
            ->sum();

        self::assertInstanceOf(MixedChain::class, $chain);
        self::assertSame(array_sum(array_pad(array_filter([1, 2, 3, 4], static fn (int $value): bool => $value > 1), 5, 0)), $chain->get());
    }

    public function testComplexHandoffStringArrayStringArrayMixedPipeline(): void
    {
        $input = '  alpha,beta,omega,beta  ';

        $expected = array_search(
            'BE',
            explode('T', strtoupper(array_search(1, array_flip(array_reverse(explode(',', trim($input)), false)), true))),
            true,
        );

        $actual = Str::of($input)
            ->trim()
            ->split(',')
            ->reverse()
            ->flip()
            ->search(1, true)
            ->toupper()
            ->split('T')
            ->search('BE', true);

        self::assertInstanceOf(MixedChain::class, $actual);
        self::assertSame($expected, $actual->get());
    }

    public function testComplexHandoffEndsWithFalseReturnsMixedChain(): void
    {
        $input = ['first' => 'Alpha', 'second' => 'beta', 'third' => 'gamma'];

        $expected = str_ends_with(
            strtoupper(
                array_search(
                    'beta',
                    array_reverse($input, true),
                    true,
                ),
            ),
            'X',
        );

        $actual = Arr::of($input)
            ->reverse(true)
            ->search('beta', true)
            ->toupper()
            ->endsWith('X');

        self::assertInstanceOf(MixedChain::class, $actual);
        self::assertFalse($actual->get());
        self::assertSame($expected, $actual());
    }

    public function testComplexHandoffSearchMissReturnsFalseInMixedChain(): void
    {
        $expected = array_search(
            'missing',
            explode('e', strtolower(array_search('y', ['Alpha' => 'x', 'Beta' => 'y'], true))),
            true,
        );

        $actual = Arr::of(['Alpha' => 'x', 'Beta' => 'y'])
            ->search('y', true)
            ->tolower()
            ->split('e')
            ->search('missing', true);

        self::assertInstanceOf(MixedChain::class, $actual);
        self::assertFalse($actual->get());
        self::assertSame($expected, $actual->get());
    }

    public function testChainCanMoveFromStringToArrayToScalar(): void
    {
        $input = '  alpha,beta,gamma  ';

        $expected = array_search('beta', array_values(explode(',', trim($input))), false);
        $actual = Str::of($input)
            ->trim()
            ->split(',')
            ->values()
            ->search('beta')
            ->get();

        self::assertSame($expected, $actual);
    }

    public function testInvokeReturnsSameValueAsGet(): void
    {
        $chain = Str::of('  Foo,Bar  ')
            ->trim()
            ->tolower()
            ->split(',');

        self::assertSame($chain->get(), $chain());
    }

    public function testStringSplitHandsOffToArrayChain(): void
    {
        $chain = Str::of('alpha,beta')->split(',');

        self::assertInstanceOf(ArrayChain::class, $chain);
        self::assertSame(['alpha', 'beta'], $chain->get());
    }

    public function testArraySearchCanHandOffToStringChain(): void
    {
        $chain = Arr::of(['first' => 'alpha', 'second' => 'beta'])->search('beta');

        self::assertInstanceOf(StringChain::class, $chain);
        self::assertSame('SECOND', $chain->toupper()->get());
    }

    public function testScalarArrayResultsUseMixedChain(): void
    {
        $chain = Arr::of([1, 2, 3])->sum();

        self::assertInstanceOf(MixedChain::class, $chain);
        self::assertSame(array_sum([1, 2, 3]), $chain->get());
    }

    public function testMixedChainExposesFluentJsonBridgeMethods(): void
    {
        self::assertTrue(method_exists(MixedChain::class, 'jsonEncode'));
        self::assertTrue(method_exists(MixedChain::class, 'jsonDecode'));
    }
}
