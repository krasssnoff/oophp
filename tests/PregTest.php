<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Preg;
use Oophp\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PregTest extends TestCase
{
    public function testPregDomainRemainsStaticOnlyEntry(): void
    {
        self::assertFalse(method_exists(Preg::class, 'of'));
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
            'preg_split' => [
                preg_split('/\s*,\s*/', 'a, b, c', -1, PREG_SPLIT_NO_EMPTY),
                Preg::pregSplit('/\s*,\s*/', 'a, b, c', -1, PREG_SPLIT_NO_EMPTY),
            ],
            'preg_grep' => [
                preg_grep('/^a/u', ['alpha', 'beta', 'axis']),
                Preg::pregGrep('/^a/u', ['alpha', 'beta', 'axis']),
            ],
            'preg_quote' => [
                preg_quote('a+b?c', '/'),
                Preg::pregQuote('a+b?c', '/'),
            ],
            'preg_replace_callback' => [
                preg_replace_callback('/(\d+)/', static fn (array $m): string => '[' . $m[1] . ']', 'id=42'),
                Preg::pregReplaceCallback('/(\d+)/', static fn (array $m): string => '[' . $m[1] . ']', 'id=42'),
            ],
        ];
    }

    public function testPregReplaceAndCountOutputConformance(): void
    {
        $expectedCount = null;
        $actualCount = null;

        $expected = preg_replace('/\s+/', '-', 'hello world', -1, $expectedCount);
        $actual = Preg::pregReplace('/\s+/', '-', 'hello world', -1, $actualCount);

        self::assertSame($expected, $actual);
        self::assertSame($expectedCount, $actualCount);
    }

    public function testPregMatchAndMatchesOutputConformance(): void
    {
        $expectedMatches = [];
        $actualMatches = [];

        $expected = preg_match('/(\w+)-(\d+)/', 'item-42', $expectedMatches);
        $actual = Preg::pregMatch('/(\w+)-(\d+)/', 'item-42', $actualMatches);

        self::assertSame($expected, $actual);
        self::assertSame($expectedMatches, $actualMatches);
    }

    public function testPregMatchAllAndMatchesOutputConformance(): void
    {
        $expectedMatches = [];
        $actualMatches = [];

        $expected = preg_match_all('/(\w+)/', 'alpha beta', $expectedMatches, PREG_PATTERN_ORDER);
        $actual = Preg::pregMatchAll('/(\w+)/', 'alpha beta', $actualMatches, PREG_PATTERN_ORDER);

        self::assertSame($expected, $actual);
        self::assertSame($expectedMatches, $actualMatches);
    }

    public function testStringChainPregReplaceMatchesNativePhp(): void
    {
        $expected = preg_replace('/\s+/', '-', 'hello world');
        $actual = Str::of('hello world')->pregReplace('/\s+/', '-')->get();

        self::assertSame($expected, $actual);
    }

    public function testStringChainPregSplitMatchesNativePhp(): void
    {
        $expected = preg_split('/\s*,\s*/', 'a, b, c', -1, PREG_SPLIT_NO_EMPTY);
        $actual = Str::of('a, b, c')->pregSplit('/\s*,\s*/', -1, PREG_SPLIT_NO_EMPTY)->get();

        self::assertSame($expected, $actual);
    }
}
