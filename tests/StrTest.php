<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StrTest extends TestCase
{
    public function testStaticReplaceMatchesNativePhp(): void
    {
        $expected = str_replace('World', 'PHP', 'Hello World');
        $actual = Str::replace('World', 'PHP', 'Hello World');

        self::assertSame($expected, $actual);
    }

    public function testFluentStringPipelineMatchesNativePhp(): void
    {
        $input = '  Foo,Bar  ';

        $expected = explode(',', strtolower(trim($input)));
        $actual = Str::of($input)->trim()->tolower()->split(',')->get();

        self::assertSame($expected, $actual);
    }

    public function testContainsReturnsNativeResult(): void
    {
        self::assertSame(str_contains('package', 'ack'), Str::contains('package', 'ack'));
    }

    #[DataProvider('fluentProvider')]
    public function testFluentMethodsMatchNativePhp(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function fluentProvider(): array
    {
        return [
            'starts_with' => [str_starts_with('package', 'pack'), Str::of('package')->startsWith('pack')->get()],
            'ends_with' => [str_ends_with('package', 'age'), Str::of('package')->endsWith('age')->get()],
            'len' => [strlen('hello'), Str::of('hello')->len()->get()],
            'pos' => [strpos('banana', 'na', 3), Str::of('banana')->pos('na', 3)->get()],
            'ipos' => [stripos('BaNaNa', 'na', 3), Str::of('BaNaNa')->ipos('na', 3)->get()],
            'rpos' => [strrpos('banana', 'na'), Str::of('banana')->rpos('na')->get()],
            'ripos' => [strripos('BaNaNa', 'NA'), Str::of('BaNaNa')->ripos('NA')->get()],
            'repeat' => [str_repeat('ab', 3), Str::of('ab')->repeat(3)->get()],
            'rev' => [strrev('desserts'), Str::of('desserts')->rev()->get()],
            'substr' => [substr('package', 1, 3), Str::of('package')->substr(1, 3)->get()],
            'substr_count' => [substr_count('banana', 'na', 1, 4), Str::of('banana')->substrCount('na', 1, 4)->get()],
            'substr_replace' => [substr_replace('abcdef', 'X', 2, 3), Str::of('abcdef')->substrReplace('X', 2, 3)->get()],
        ];
    }

    #[DataProvider('edgeCaseProvider')]
    public function testStringEdgeCasesPreserveNativeBehavior(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function edgeCaseProvider(): array
    {
        return [
            'trim_character_mask' => [trim('__test__', '_'), Str::trim('__test__', '_')],
            'contains_empty_needle' => [str_contains('package', ''), Str::contains('package', '')],
            'starts_with_empty_needle' => [str_starts_with('package', ''), Str::startsWith('package', '')],
            'ends_with_empty_needle' => [str_ends_with('package', ''), Str::endsWith('package', '')],
            'pos_not_found' => [strpos('banana', 'zz'), Str::pos('banana', 'zz')],
            'ipos_not_found_after_offset' => [stripos('BaNaNa', 'ba', 1), Str::ipos('BaNaNa', 'ba', 1)],
            'rpos_negative_offset' => [strrpos('banana', 'a', -2), Str::rpos('banana', 'a', -2)],
            'ripos_not_found' => [strripos('BaNaNa', 'zz'), Str::ripos('BaNaNa', 'zz')],
            'repeat_zero_times' => [str_repeat('ab', 0), Str::repeat('ab', 0)],
            'substr_negative_offset' => [substr('package', -3), Str::substr('package', -3)],
            'substr_count_window' => [substr_count('banana', 'na', 1, 4), Str::substrCount('banana', 'na', 1, 4)],
            'substr_replace_array_subject' => [substr_replace(['abc', 'def'], 'X', 1, 1), Str::substrReplace(['abc', 'def'], 'X', 1, 1)],
            'split_negative_limit' => [explode(',', 'a,b,c', -1), Str::split(',', 'a,b,c', -1)],
        ];
    }
}
