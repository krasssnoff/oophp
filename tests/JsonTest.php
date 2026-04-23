<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Json;
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testJsonRemainsStaticOnlyDomain(): void
    {
        self::assertFalse(method_exists(Json::class, 'of'));
    }

    public function testStaticEncodeMatchesNativePhp(): void
    {
        $value = ['ok' => true, 'count' => 2];

        self::assertSame(json_encode($value, 0, 512), Json::encode($value));
    }

    public function testStaticDecodeMatchesNativePhp(): void
    {
        $json = '{"ok":true,"count":2}';

        self::assertSame(json_decode($json, true, 512, 0), Json::decode($json));
    }

    public function testStaticValidateMatchesNativePhp(): void
    {
        $json = '{"ok":true,"count":2}';

        self::assertSame(json_validate($json), Json::validate($json));
    }
}
