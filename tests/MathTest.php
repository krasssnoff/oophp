<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Chain\NumberChain;
use Oophp\Math;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MathTest extends TestCase
{
    public function testMathExposesNumberChainEntryPoint(): void
    {
        self::assertTrue(method_exists(Math::class, 'of'));
        self::assertInstanceOf(NumberChain::class, Math::of(10));
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
        return [
            'abs_int' => [abs(-5), Math::abs(-5)],
            'abs_float' => [abs(-3.14), Math::abs(-3.14)],
            'ceil' => [ceil(2.1), Math::ceil(2.1)],
            'floor' => [floor(2.9), Math::floor(2.9)],
            'round_half_up' => [round(2.55, 1, PHP_ROUND_HALF_UP), Math::round(2.55, 1, PHP_ROUND_HALF_UP)],
            'max_scalars' => [max(10, 2, 7), Math::max(10, 2, 7)],
            'min_scalars' => [min(10, 2, 7), Math::min(10, 2, 7)],
            'pow' => [pow(2, 8), Math::pow(2, 8)],
            'sqrt' => [sqrt(81), Math::sqrt(81)],
            'fmod' => [fmod(5.7, 1.3), Math::fmod(5.7, 1.3)],
            'intdiv' => [intdiv(20, 3), Math::intdiv(20, 3)],
        ];
    }

    public function testIntdivExceptionMatchesNativePhp(): void
    {
        $this->expectException(\DivisionByZeroError::class);
        Math::intdiv(1, 0);
    }

    public function testNumberChainMatchesNativeMathPipeline(): void
    {
        $expected = sqrt(pow(round(abs(-2.55), 1, PHP_ROUND_HALF_UP), 2));

        $actual = Math::of(-2.55)
            ->abs()
            ->round(1, PHP_ROUND_HALF_UP)
            ->pow(2)
            ->sqrt()
            ->get();

        self::assertSame($expected, $actual);
    }

    public function testNumberChainMinMaxFmodAndIntdivMatchNativePhp(): void
    {
        self::assertSame(max(10, 2, 7), Math::of(10)->max(2, 7)->get());
        self::assertSame(min(10, 2, 7), Math::of(10)->min(2, 7)->get());
        self::assertSame(fmod(5.7, 1.3), Math::of(5.7)->fmod(1.3)->get());
        self::assertSame(intdiv(20, 3), Math::of(20)->intdiv(3)->get());
    }
}
