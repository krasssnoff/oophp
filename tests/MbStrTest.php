<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\MbStr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MbStrTest extends TestCase
{
    public function testStaticAndFluentPipelinesMatchNativePhp(): void
    {
        $this->skipWhenMbstringIsUnavailable();

        $input = ' ПрИвЕт ';
        $expected = mb_str_split(mb_strtolower(mb_substr($input, 1, 6, 'UTF-8'), 'UTF-8'), 1, 'UTF-8');
        $actual = MbStr::of($input)
            ->substr(1, 6, 'UTF-8')
            ->tolower('UTF-8')
            ->split(1, 'UTF-8')
            ->get();

        self::assertSame($expected, $actual);
    }

    #[DataProvider('mbStaticProvider')]
    public function testMbStaticMethodsMatchNativePhp(mixed $expected, mixed $actual): void
    {
        $this->skipWhenMbstringIsUnavailable();
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function mbStaticProvider(): array
    {
        if (!function_exists('mb_strlen')) {
            return [
                'mbstring_unavailable' => [null, null],
            ];
        }

        return [
            'tolower' => [mb_strtolower('ПрИвЕт', 'UTF-8'), MbStr::tolower('ПрИвЕт', 'UTF-8')],
            'toupper' => [mb_strtoupper('ПрИвЕт', 'UTF-8'), MbStr::toupper('ПрИвЕт', 'UTF-8')],
            'len' => [mb_strlen('Привет', 'UTF-8'), MbStr::len('Привет', 'UTF-8')],
            'pos' => [mb_strpos('До свидания', 'вид', 0, 'UTF-8'), MbStr::pos('До свидания', 'вид', 0, 'UTF-8')],
            'rpos' => [mb_strrpos('абв абв', 'абв', 0, 'UTF-8'), MbStr::rpos('абв абв', 'абв', 0, 'UTF-8')],
            'substr' => [mb_substr('Привет', 1, 3, 'UTF-8'), MbStr::substr('Привет', 1, 3, 'UTF-8')],
            'split' => [mb_str_split('Привет', 2, 'UTF-8'), MbStr::split('Привет', 2, 'UTF-8')],
            'contains' => [mb_strpos('До свидания', 'вид', 0, 'UTF-8') !== false, MbStr::contains('До свидания', 'вид', 'UTF-8')],
            'starts_with' => [mb_substr('Привет', 0, mb_strlen('При', 'UTF-8'), 'UTF-8') === 'При', MbStr::startsWith('Привет', 'При', 'UTF-8')],
            'ends_with' => [mb_substr('Привет', -mb_strlen('вет', 'UTF-8'), null, 'UTF-8') === 'вет', MbStr::endsWith('Привет', 'вет', 'UTF-8')],
        ];
    }

    public function testFluentContainsAndPrefixSuffixMatchStaticMethods(): void
    {
        $this->skipWhenMbstringIsUnavailable();

        $chain = MbStr::of('Привет');
        self::assertSame(MbStr::contains('Привет', 'рив', 'UTF-8'), $chain->contains('рив', 'UTF-8')->get());
        self::assertSame(MbStr::startsWith('Привет', 'При', 'UTF-8'), $chain->startsWith('При', 'UTF-8')->get());
        self::assertSame(MbStr::endsWith('Привет', 'вет', 'UTF-8'), $chain->endsWith('вет', 'UTF-8')->get());
    }

    private function skipWhenMbstringIsUnavailable(): void
    {
        if (!function_exists('mb_strlen')) {
            self::markTestSkipped('mbstring extension is not available.');
        }
    }
}
