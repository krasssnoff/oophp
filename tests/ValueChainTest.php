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

    public function testMixedChainDoesNotExposeFluentJsonMethods(): void
    {
        self::assertFalse(method_exists(MixedChain::class, 'jsonEncode'));
        self::assertFalse(method_exists(MixedChain::class, 'jsonDecode'));
    }
}
