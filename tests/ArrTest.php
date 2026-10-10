<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Arr;
use Oophp\Chain\ArrayChain;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ArrTest extends TestCase
{
    public function testStaticValuesMatchesNativePhp(): void
    {
        $input = ['first' => 'a', 'second' => 'b'];

        self::assertSame(array_values($input), Arr::values($input));
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
        $source = ['x' => 10, 'y' => 20];

        return [
            'keys' => [array_keys($source), Arr::keys($source)],
            'search_strict_false' => [array_search('20', [10, 20], false), Arr::search('20', [10, 20], false)],
            'search_strict_true' => [array_search('20', [10, 20], true), Arr::search('20', [10, 20], true)],
            'reverse_preserve' => [array_reverse($source, true), Arr::reverse($source, true)],
            'unique' => [array_unique(['x', 'x', 'y'], SORT_STRING), Arr::unique(['x', 'x', 'y'])],
            'chunk' => [array_chunk($source, 1, true), Arr::chunk($source, 1, true)],
            'flip' => [array_flip(['x' => 'alpha', 'y' => 'beta']), Arr::flip(['x' => 'alpha', 'y' => 'beta'])],
            'pad' => [array_pad([1, 2], 4, 0), Arr::pad([1, 2], 4, 0)],
            'combine' => [array_combine(['id', 'name'], [10, 'Ann']), Arr::combine(['id', 'name'], [10, 'Ann'])],
            'diff' => [array_diff(['a', 'b', 'c'], ['b']), Arr::diff(['a', 'b', 'c'], ['b'])],
            'intersect' => [array_intersect(['a', 'b', 'c'], ['b', 'd']), Arr::intersect(['a', 'b', 'c'], ['b', 'd'])],
            'replace' => [array_replace(['x' => 1, 'y' => 2], ['y' => 20]), Arr::replace(['x' => 1, 'y' => 2], ['y' => 20])],
            'in_array_strict_false' => [in_array('2', [1, 2], false), Arr::inArray('2', [1, 2], false)],
            'is_list_true' => [array_is_list([10, 20, 30]), Arr::isList([10, 20, 30])],
            'change_key_case_upper' => [array_change_key_case(['first' => 1], CASE_UPPER), Arr::changeKeyCase(['first' => 1], CASE_UPPER)],
            'fill_keys' => [array_fill_keys(['id', 'name'], 0), Arr::fillKeys(['id', 'name'], 0)],
            'key_last' => [array_key_last(['b' => 2, 'a' => 1]), Arr::keyLast(['b' => 2, 'a' => 1])],
            'diff_assoc' => [array_diff_assoc(['a' => 1, 'b' => 2], ['a' => 1]), Arr::diffAssoc(['a' => 1, 'b' => 2], ['a' => 1])],
            'diff_key' => [array_diff_key(['a' => 1, 'b' => 2], ['a' => 9]), Arr::diffKey(['a' => 1, 'b' => 2], ['a' => 9])],
            'intersect_assoc' => [array_intersect_assoc(['a' => 1, 'b' => 2], ['b' => 2, 'c' => 3]), Arr::intersectAssoc(['a' => 1, 'b' => 2], ['b' => 2, 'c' => 3])],
            'intersect_key' => [array_intersect_key(['a' => 1, 'b' => 2], ['b' => 9]), Arr::intersectKey(['a' => 1, 'b' => 2], ['b' => 9])],
            'sum' => [array_sum([1, 2, 3]), Arr::sum([1, 2, 3])],
            'product' => [array_product([1.5, 2, 3]), Arr::product([1.5, 2, 3])],
            'key_exists' => [array_key_exists('a', ['a' => 1]), Arr::keyExists('a', ['a' => 1])],
        ];
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
        $actual = Arr::of($base)->replace(['y' => 20])->get();

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

    public function testFluentChangeKeyCaseMatchesNativePhp(): void
    {
        $input = ['first' => 1, 'second' => 2];

        $expected = array_change_key_case($input, CASE_UPPER);
        $actual = Arr::of($input)->changeKeyCase(CASE_UPPER)->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentFillKeysMatchesNativePhp(): void
    {
        $keys = ['id', 'name'];

        $expected = array_fill_keys($keys, 0);
        $actual = Arr::of($keys)->fillKeys(0)->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentKeyFirstMatchesNativePhp(): void
    {
        $input = ['b' => 2, 'a' => 1];

        $expected = array_key_first($input);
        $actual = Arr::of($input)->keyFirst()->get();

        self::assertSame($expected, $actual);
    }

    public function testKeyFirstOnEmptyArrayReturnsNull(): void
    {
        self::assertSame(array_key_first([]), Arr::keyFirst([]));
    }

    public function testFluentKeyLastMatchesNativePhp(): void
    {
        $input = ['b' => 2, 'a' => 1];

        $expected = array_key_last($input);
        $actual = Arr::of($input)->keyLast()->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentDiffAssocMatchesNativePhp(): void
    {
        $input = ['a' => 1, 'b' => 2];

        $expected = array_diff_assoc($input, ['a' => 1]);
        $actual = Arr::of($input)->diffAssoc(['a' => 1])->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentDiffKeyMatchesNativePhp(): void
    {
        $input = ['a' => 1, 'b' => 2];

        $expected = array_diff_key($input, ['a' => 9]);
        $actual = Arr::of($input)->diffKey(['a' => 9])->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentIntersectAssocMatchesNativePhp(): void
    {
        $input = ['a' => 1, 'b' => 2];

        $expected = array_intersect_assoc($input, ['b' => 2, 'c' => 3]);
        $actual = Arr::of($input)->intersectAssoc(['b' => 2, 'c' => 3])->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentIntersectKeyMatchesNativePhp(): void
    {
        $input = ['a' => 1, 'b' => 2];

        $expected = array_intersect_key($input, ['b' => 9]);
        $actual = Arr::of($input)->intersectKey(['b' => 9])->get();

        self::assertSame($expected, $actual);
    }

    public function testStaticReplaceRecursiveMatchesNativePhp(): void
    {
        $base = ['cfg' => ['a' => 1, 'nested' => ['x' => 1]]];
        $replacement = ['cfg' => ['nested' => ['y' => 2]]];

        self::assertSame(
            array_replace_recursive($base, $replacement),
            Arr::replaceRecursive($base, $replacement),
        );
    }

    public function testFluentSumReturnsNativeScalar(): void
    {
        $input = [1, 2.5, 3];

        $expected = array_sum($input);
        $actual = Arr::of($input)->sum()->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentProductReturnsNativeScalar(): void
    {
        $input = [1.5, 2, 3];

        $expected = array_product($input);
        $actual = Arr::of($input)->product()->get();

        self::assertSame($expected, $actual);
    }

    public function testFluentKeyExistsReturnsNativeBoolean(): void
    {
        $input = ['a' => 1, 'b' => 2];

        $expected = array_key_exists('b', $input);
        $actual = Arr::of($input)->keyExists('b')->get();

        self::assertSame($expected, $actual);
    }

    public function testReduceSortRsortAndImplodeMatchNativePhp(): void
    {
        $input = [3, 1, 2];

        self::assertSame(
            array_reduce($input, static fn (int $carry, int $item): int => $carry + $item, 0),
            Arr::reduce($input, static fn (int $carry, int $item): int => $carry + $item, 0),
        );

        $native = $input;
        $wrapped = $input;
        self::assertSame(sort($native, SORT_NUMERIC), Arr::sort($wrapped, SORT_NUMERIC));
        self::assertSame($native, $wrapped);
        self::assertSame($native, Arr::of($input)->sort(SORT_NUMERIC)->get());

        $native = $input;
        $wrapped = $input;
        self::assertSame(rsort($native, SORT_NUMERIC), Arr::rsort($wrapped, SORT_NUMERIC));
        self::assertSame($native, $wrapped);
        self::assertSame($native, Arr::of($input)->rsort(SORT_NUMERIC)->get());

        self::assertSame(implode('-', ['a', 'b', 'c']), Arr::implode('-', ['a', 'b', 'c']));
        self::assertSame(implode(['a', 'b', 'c']), Arr::implode(['a', 'b', 'c']));
        self::assertSame(implode('-', ['a', 'b', 'c']), Arr::of(['a', 'b', 'c'])->implode('-')->get());
    }

    public function testStaticCallbackDiffAndIntersectVariantsMatchNativePhp(): void
    {
        $valueCompare = static fn (mixed $left, mixed $right): int => $left <=> $right;
        $keyCompare = static fn (mixed $left, mixed $right): int => $left <=> $right;

        self::assertSame(
            array_diff_uassoc(['a' => 1, 'b' => 2], ['a' => 1], $keyCompare),
            Arr::diffUassoc(['a' => 1, 'b' => 2], ['a' => 1], $keyCompare),
        );
        self::assertSame(
            array_diff_ukey(['a' => 1, 'b' => 2], ['a' => 9], $keyCompare),
            Arr::diffUkey(['a' => 1, 'b' => 2], ['a' => 9], $keyCompare),
        );
        self::assertSame(
            array_udiff(['a', 'b', 'c'], ['b'], $valueCompare),
            Arr::udiff(['a', 'b', 'c'], ['b'], $valueCompare),
        );
        self::assertSame(
            array_udiff_assoc(['a' => 1, 'b' => 2], ['a' => 1], $valueCompare),
            Arr::udiffAssoc(['a' => 1, 'b' => 2], ['a' => 1], $valueCompare),
        );
        self::assertSame(
            array_udiff_uassoc(['a' => 1, 'b' => 2], ['a' => 1], $valueCompare, $keyCompare),
            Arr::udiffUassoc(['a' => 1, 'b' => 2], ['a' => 1], $valueCompare, $keyCompare),
        );
        self::assertSame(
            array_intersect_uassoc(['a' => 1, 'b' => 2], ['b' => 2], $keyCompare),
            Arr::intersectUassoc(['a' => 1, 'b' => 2], ['b' => 2], $keyCompare),
        );
        self::assertSame(
            array_intersect_ukey(['a' => 1, 'b' => 2], ['b' => 9], $keyCompare),
            Arr::intersectUkey(['a' => 1, 'b' => 2], ['b' => 9], $keyCompare),
        );
        self::assertSame(
            array_uintersect(['a', 'b', 'c'], ['b'], $valueCompare),
            Arr::uintersect(['a', 'b', 'c'], ['b'], $valueCompare),
        );
        self::assertSame(
            array_uintersect_assoc(['a' => 1, 'b' => 2], ['b' => 2], $valueCompare),
            Arr::uintersectAssoc(['a' => 1, 'b' => 2], ['b' => 2], $valueCompare),
        );
        self::assertSame(
            array_uintersect_uassoc(['a' => 1, 'b' => 2], ['b' => 2], $valueCompare, $keyCompare),
            Arr::uintersectUassoc(['a' => 1, 'b' => 2], ['b' => 2], $valueCompare, $keyCompare),
        );
    }

    public function testStaticMutatorsPreserveNativeReturnAndMutation(): void
    {
        $native = [1, 2, 3];
        $wrapped = [1, 2, 3];
        self::assertSame(array_pop($native), Arr::pop($wrapped));
        self::assertSame($native, $wrapped);

        $native = [1, 2];
        $wrapped = [1, 2];
        self::assertSame(array_push($native, 3, 4), Arr::push($wrapped, 3, 4));
        self::assertSame($native, $wrapped);

        $native = [1, 2, 3];
        $wrapped = [1, 2, 3];
        self::assertSame(array_shift($native), Arr::shift($wrapped));
        self::assertSame($native, $wrapped);

        $native = [2, 3];
        $wrapped = [2, 3];
        self::assertSame(array_unshift($native, 0, 1), Arr::unshift($wrapped, 0, 1));
        self::assertSame($native, $wrapped);

        $native = ['a', 'b', 'c', 'd'];
        $wrapped = ['a', 'b', 'c', 'd'];
        self::assertSame(array_splice($native, 1, 2, ['x', 'y']), Arr::splice($wrapped, 1, 2, ['x', 'y']));
        self::assertSame($native, $wrapped);

        $callback = static function (int &$value): void {
            $value *= 2;
        };
        $native = [1, 2, 3];
        $wrapped = [1, 2, 3];
        self::assertSame(array_walk($native, $callback), Arr::walk($wrapped, $callback));
        self::assertSame($native, $wrapped);

        $native = ['nested' => [1, 2]];
        $wrapped = ['nested' => [1, 2]];
        self::assertSame(array_walk_recursive($native, $callback), Arr::walkRecursive($wrapped, $callback));
        self::assertSame($native, $wrapped);
    }

    public function testFluentMutatorsReturnUpdatedArray(): void
    {
        self::assertSame([1, 2, 3, 4], Arr::of([1, 2])->push(3, 4)->get());
        self::assertSame([0, 1, 2, 3], Arr::of([2, 3])->unshift(0, 1)->get());
        self::assertSame(['a', 'x', 'y', 'd'], Arr::of(['a', 'b', 'c', 'd'])->splice(1, 2, ['x', 'y'])->get());
        self::assertSame([2, 4, 6], Arr::of([1, 2, 3])->walk(static function (int &$value): void {
            $value *= 2;
        })->get());
        self::assertSame(['nested' => [2, 4]], Arr::of(['nested' => [1, 2]])->walkRecursive(static function (int &$value): void {
            $value *= 2;
        })->get());
    }

    public function testFluentPopAndShiftReturnExtractedElement(): void
    {
        self::assertSame('last', Arr::of(['first', 'last'])->pop()->get());
        self::assertSame('first', Arr::of(['first', 'last'])->shift()->get());
        self::assertSame(['id' => 2], Arr::of([['id' => 1], ['id' => 2]])->pop()->get());
        self::assertSame(['id' => 1], Arr::of([['id' => 1], ['id' => 2]])->shift()->get());
    }

    /**
     * @return array<string, array{array<mixed>, \Closure, \Closure, \Closure}>
     */
    public static function sortProvider(): array
    {
        $assoc = ['b' => 2, 'a' => 1, 'c' => 3];
        $descending = static fn (mixed $left, mixed $right): int => $right <=> $left;

        return [
            'asort' => [$assoc, static fn (array &$a): bool => asort($a, SORT_NUMERIC), static fn (array &$a): bool => Arr::asort($a, SORT_NUMERIC), static fn (array $a): ArrayChain => Arr::of($a)->asort(SORT_NUMERIC)],
            'arsort' => [$assoc, static fn (array &$a): bool => arsort($a, SORT_NUMERIC), static fn (array &$a): bool => Arr::arsort($a, SORT_NUMERIC), static fn (array $a): ArrayChain => Arr::of($a)->arsort(SORT_NUMERIC)],
            'ksort' => [$assoc, static fn (array &$a): bool => ksort($a, SORT_STRING), static fn (array &$a): bool => Arr::ksort($a, SORT_STRING), static fn (array $a): ArrayChain => Arr::of($a)->ksort(SORT_STRING)],
            'krsort' => [$assoc, static fn (array &$a): bool => krsort($a, SORT_STRING), static fn (array &$a): bool => Arr::krsort($a, SORT_STRING), static fn (array $a): ArrayChain => Arr::of($a)->krsort(SORT_STRING)],
            'natsort' => [['img12', 'img10', 'img2', 'img1'], static fn (array &$a): bool => natsort($a), static fn (array &$a): bool => Arr::natSort($a), static fn (array $a): ArrayChain => Arr::of($a)->natSort()],
            'natcasesort' => [['A10', 'a2', 'A1'], static fn (array &$a): bool => natcasesort($a), static fn (array &$a): bool => Arr::natCaseSort($a), static fn (array $a): ArrayChain => Arr::of($a)->natCaseSort()],
            'shuffle' => [['only'], static fn (array &$a): bool => shuffle($a), static fn (array &$a): bool => Arr::shuffle($a), static fn (array $a): ArrayChain => Arr::of($a)->shuffle()],
            'uasort' => [$assoc, static fn (array &$a): bool => uasort($a, $descending), static fn (array &$a): bool => Arr::uasort($a, $descending), static fn (array $a): ArrayChain => Arr::of($a)->uasort($descending)],
            'uksort' => [$assoc, static fn (array &$a): bool => uksort($a, $descending), static fn (array &$a): bool => Arr::uksort($a, $descending), static fn (array $a): ArrayChain => Arr::of($a)->uksort($descending)],
            'usort' => [[3, 1, 2], static fn (array &$a): bool => usort($a, $descending), static fn (array &$a): bool => Arr::usort($a, $descending), static fn (array $a): ArrayChain => Arr::of($a)->usort($descending)],
            'array_multisort' => [[3, 1, 2], static fn (array &$a): bool => array_multisort($a, SORT_ASC, SORT_NUMERIC), static fn (array &$a): bool => Arr::multiSort($a, SORT_ASC, SORT_NUMERIC), static fn (array $a): ArrayChain => Arr::of($a)->multiSort(SORT_ASC, SORT_NUMERIC)],
        ];
    }

    #[DataProvider('sortProvider')]
    public function testSortsMutateByReferenceLikeNativePhp(array $input, \Closure $native, \Closure $wrapped, \Closure $chain): void
    {
        $expected = $input;
        $actual = $input;

        self::assertSame($native($expected), $wrapped($actual));
        self::assertSame($expected, $actual);
        self::assertSame($expected, $chain($input)->get());
    }

    public function testMultiSortSortsOnlyTheFirstArray(): void
    {
        $data = [3, 1, 2];
        $other = ['c', 'a', 'b'];

        self::assertTrue(Arr::multiSort($data, $other));
        self::assertSame([1, 2, 3], $data);
        self::assertSame(['c', 'a', 'b'], $other);
    }

    public function testKeysFilterValueMatchesNativePhp(): void
    {
        $input = ['a' => 1, 'b' => '1', 'c' => 2];

        self::assertSame(array_keys($input, 1), Arr::keys($input, 1));
        self::assertSame(array_keys($input, 1, true), Arr::keys($input, 1, true));
        self::assertSame(array_keys($input, null), Arr::keys($input, null));
        self::assertSame(array_keys($input, 1, true), Arr::of($input)->keys(1, true)->get());
    }

    public function testNativeArgumentShapesMatchNativePhp(): void
    {
        self::assertSame(array_merge(), Arr::merge());
        self::assertSame(array_merge_recursive(), Arr::mergeRecursive());
        self::assertSame(array_key_exists(1.0, [1 => 'x']), Arr::keyExists(1.0, [1 => 'x']));

        $native = [1, 2, 3];
        $wrapped = [1, 2, 3];
        self::assertSame(array_splice($native, 1, 1, 'x'), Arr::splice($wrapped, 1, 1, 'x'));
        self::assertSame($native, $wrapped);
    }

    public function testFillRandAndFluentCallbackVariantsMatchNativePhp(): void
    {
        $valueCompare = static fn (mixed $left, mixed $right): int => $left <=> $right;
        $keyCompare = static fn (mixed $left, mixed $right): int => $left <=> $right;

        self::assertSame(array_fill(2, 3, 'x'), Arr::fill(2, 3, 'x'));
        self::assertSame(array_rand(['a' => 1, 'b' => 2], 2), Arr::rand(['a' => 1, 'b' => 2], 2));
        self::assertSame(array_rand(['a' => 1, 'b' => 2], 2), Arr::of(['a' => 1, 'b' => 2])->rand(2)->get());

        self::assertSame(
            array_diff_uassoc(['a' => 1, 'b' => 2], ['a' => 1], $keyCompare),
            Arr::of(['a' => 1, 'b' => 2])->diffUassoc(['a' => 1], $keyCompare)->get(),
        );
        self::assertSame(
            array_udiff_uassoc(['a' => 1, 'b' => 2], ['a' => 1], $valueCompare, $keyCompare),
            Arr::of(['a' => 1, 'b' => 2])->udiffUassoc(['a' => 1], $valueCompare, $keyCompare)->get(),
        );
        self::assertSame(
            array_uintersect_uassoc(['a' => 1, 'b' => 2], ['b' => 2], $valueCompare, $keyCompare),
            Arr::of(['a' => 1, 'b' => 2])->uintersectUassoc(['b' => 2], $valueCompare, $keyCompare)->get(),
        );
    }
}
