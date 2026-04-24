<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Math;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MathTest extends TestCase
{
    public function testMathRemainsStaticOnlyDomain(): void
    {
        self::assertFalse(method_exists(Math::class, 'of'));
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
}
