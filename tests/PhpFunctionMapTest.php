<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Support\PhpFunctionMap;
use PHPUnit\Framework\TestCase;

final class PhpFunctionMapTest extends TestCase
{
    public function testDefinitionsContainCoreV1Functions(): void
    {
        $definitions = PhpFunctionMap::definitions();

        self::assertArrayHasKey('array_values', $definitions);
        self::assertArrayHasKey('str_replace', $definitions);
        self::assertArrayHasKey('json_encode', $definitions);
        self::assertArrayHasKey('getenv', $definitions);
    }

    public function testArraySearchIsMarkedAsMixedReturn(): void
    {
        $definitions = PhpFunctionMap::definitions();

        self::assertTrue($definitions['array_search']['mixed_return']);
        self::assertSame('search', $definitions['array_search']['chain_method']);
    }

    public function testSysFunctionsAreMarkedAsSideEffectDomain(): void
    {
        $definitions = PhpFunctionMap::definitions();

        self::assertTrue($definitions['getenv']['side_effect']);
        self::assertNull($definitions['getenv']['chain_method']);
    }
}
