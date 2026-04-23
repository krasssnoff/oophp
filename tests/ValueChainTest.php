<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Json;
use Oophp\Str;
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

    public function testJsonHelpersKeepRawPhpSemantics(): void
    {
        $expected = json_decode(json_encode(['a' => 1], 0, 512), true, 512, 0);
        $actual = Json::of(['a' => 1])
            ->jsonEncode()
            ->jsonDecode()
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
}
