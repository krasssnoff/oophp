<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Arr;
use Oophp\Json;
use Oophp\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConformanceTest extends TestCase
{
    #[DataProvider('arrStaticProvider')]
    public function testArrStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function arrStaticProvider(): array
    {
        $source = ['x' => 10, 'y' => 20];

        return [
            'values' => [array_values($source), Arr::values($source)],
            'keys' => [array_keys($source), Arr::keys($source)],
            'search_strict_false' => [array_search('20', [10, 20], false), Arr::search('20', [10, 20], false)],
            'search_strict_true' => [array_search('20', [10, 20], true), Arr::search('20', [10, 20], true)],
            'reverse_preserve' => [array_reverse($source, true), Arr::reverse($source, true)],
        ];
    }

    #[DataProvider('strStaticProvider')]
    public function testStrStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function strStaticProvider(): array
    {
        return [
            'replace' => [str_replace('a', 'b', 'a-cat'), Str::replace('a', 'b', 'a-cat')],
            'lower' => [strtolower('TeSt'), Str::lower('TeSt')],
            'upper' => [strtoupper('TeSt'), Str::upper('TeSt')],
            'trim' => [trim('  test  '), Str::trim('  test  ')],
            'contains_true' => [str_contains('package', 'ack'), Str::contains('package', 'ack')],
            'split_limit' => [explode(',', 'a,b,c', 2), Str::split(',', 'a,b,c', 2)],
        ];
    }

    #[DataProvider('jsonStaticProvider')]
    public function testJsonStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function jsonStaticProvider(): array
    {
        $payload = ['ok' => true, 'id' => 1];
        $encoded = '{"ok":true,"id":1}';

        return [
            'encode' => [json_encode($payload, 0, 512), Json::encode($payload)],
            'decode' => [json_decode($encoded, true, 512, 0), Json::decode($encoded)],
            'validate' => [json_validate($encoded), Json::validate($encoded)],
        ];
    }

    public function testFluentConformanceAcrossTypeHandoff(): void
    {
        $input = '  alpha,beta,gamma  ';
        $expected = array_search('beta', array_values(explode(',', strtolower(trim($input)))), false);

        $actual = Str::of($input)
            ->trim()
            ->lower()
            ->split(',')
            ->values()
            ->search('beta')
            ->get();

        self::assertSame($expected, $actual);
    }

    public function testInvokeAndGetReturnSameTerminalValue(): void
    {
        $chain = Arr::of(['a' => 'x', 'b' => 'y'])->values()->search('y');

        self::assertSame($chain->get(), $chain());
    }
}
