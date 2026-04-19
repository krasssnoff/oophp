<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Str;
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
        $actual = Str::of($input)->trim()->lower()->split(',')->get();

        self::assertSame($expected, $actual);
    }

    public function testContainsReturnsNativeResult(): void
    {
        self::assertSame(str_contains('package', 'ack'), Str::contains('package', 'ack'));
    }
}
