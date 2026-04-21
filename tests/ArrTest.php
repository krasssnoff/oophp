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

    public function testInvokeCanBeUsedInsteadOfGet(): void
    {
        $input = ['first' => 'a', 'second' => 'b'];

        $expected = array_search('b', array_values($input), false);
        $actual = Arr::of($input)->values()->search('b')();

        self::assertSame($expected, $actual);
    }

    public function testStaticMergeMatchesNativePhp(): void
    {
        $base = ['a' => 1, 'b' => 2];
        $extra = ['b' => 20, 'c' => 3];

        self::assertSame(array_merge($base, $extra), Arr::merge($base, $extra));
    }

    public function testFluentSliceMatchesNativePhp(): void
    {
        $input = ['x' => 'a', 'y' => 'b', 'z' => 'c', 'w' => 'd'];

        $expected = array_slice($input, 1, 2, true);
        $actual = Arr::of($input)->slice(1, 2, true)->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentUniqueMatchesNativePhp(): void
    {
        $input = ['a', 'b', 'a', 'c', 'b'];

        $expected = array_unique($input, SORT_STRING);
        $actual = Arr::of($input)->unique()->get();

        self::assertSame($expected, $actual);
    }

    public function testSliceWithNullLengthPreservesNativeBehavior(): void
    {
        $input = [10, 20, 30, 40];

        $expected = array_slice($input, 2, null, false);
        $actual = Arr::slice($input, 2);

        self::assertSame($expected, $actual);
    }

    public function testFluentChunkMatchesNativePhp(): void
    {
        $input = ['x' => 10, 'y' => 20, 'z' => 30];

        $expected = array_chunk($input, 2, true);
        $actual = Arr::of($input)->chunk(2, true)->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentFlipMatchesNativePhp(): void
    {
        $input = ['a' => 'alpha', 'b' => 'beta'];

        $expected = array_flip($input);
        $actual = Arr::of($input)->flip()->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentPadMatchesNativePhp(): void
    {
        $input = [1, 2];

        $expected = array_pad($input, 5, 0);
        $actual = Arr::of($input)->pad(5, 0)->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentCombineMatchesNativePhp(): void
    {
        $keys = ['id', 'name'];
        $values = [1, 'john'];

        $expected = array_combine($keys, $values);
        $actual = Arr::of($keys)->combine($values)->get();

        self::assertSame($expected, $actual);
    }

    public function testStaticMergeRecursiveMatchesNativePhp(): void
    {
        $left = ['cfg' => ['a' => 1]];
        $right = ['cfg' => ['b' => 2]];

        self::assertSame(array_merge_recursive($left, $right), Arr::mergeRecursive($left, $right));
    }

    public function testStaticColumnMatchesNativePhp(): void
    {
        $rows = [
            ['id' => 10, 'name' => 'Ann'],
            ['id' => 20, 'name' => 'Bob'],
        ];

        self::assertSame(array_column($rows, 'name', 'id'), Arr::column($rows, 'name', 'id'));
    }

    public function testColumnNullColumnKeyPreservesNativeBehavior(): void
    {
        $rows = [
            ['id' => 10, 'name' => 'Ann'],
            ['id' => 20, 'name' => 'Bob'],
        ];

        $expected = array_column($rows, null, 'id');
        $actual = Arr::column($rows, null, 'id');

        self::assertSame($expected, $actual);
    }

    public function testFluentDiffMatchesNativePhp(): void
    {
        $input = ['a', 'b', 'c'];

        $expected = array_diff($input, ['b']);
        $actual = Arr::of($input)->diff(['b'])->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentIntersectMatchesNativePhp(): void
    {
        $input = ['a', 'b', 'c'];

        $expected = array_intersect($input, ['b', 'd']);
        $actual = Arr::of($input)->intersect(['b', 'd'])->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentReplaceArrayMatchesNativePhp(): void
    {
        $base = ['x' => 1, 'y' => 2];

        $expected = array_replace($base, ['y' => 20]);
        $actual = Arr::of($base)->replaceArray(['y' => 20])->get();

        self::assertSame($expected, $actual);
    }

    public function testStaticCountValuesMatchesNativePhp(): void
    {
        $input = ['a', 'b', 'a', 'c', 'b', 'a'];

        self::assertSame(array_count_values($input), Arr::countValues($input));
    }

    public function testFluentInArrayReturnsNativeBoolean(): void
    {
        $haystack = [1, 2, 3];

        $expected = in_array('2', $haystack, false);
        $actual = Arr::of($haystack)->inArray('2')->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentIsListReturnsNativeBoolean(): void
    {
        $input = ['x' => 10, 'y' => 20];

        $expected = array_is_list($input);
        $actual = Arr::of($input)->isList()->get();

        self::assertSame($expected, $actual);
    }
}
