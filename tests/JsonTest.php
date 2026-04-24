<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Chain\MixedChain;
use Oophp\Json;
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testJsonRemainsStaticOnlyDomain(): void
    {
        self::assertFalse(method_exists(Json::class, 'of'));
        self::assertTrue(method_exists(MixedChain::class, 'jsonEncode'));
        self::assertTrue(method_exists(MixedChain::class, 'jsonDecode'));
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

    public function testLastErrorAndMessageMatchNativePhpAfterInvalidDecode(): void
    {
        $invalidJson = '{"broken": }';

        json_decode($invalidJson, true, 512, 0);
        $expectedError = json_last_error();
        $expectedMessage = json_last_error_msg();

        Json::decode($invalidJson);
        $actualError = Json::lastError();
        $actualMessage = Json::lastErrorMessage();

        self::assertSame($expectedError, $actualError);
        self::assertSame($expectedMessage, $actualMessage);
    }

    public function testMixedChainJsonBridgeSupportsRoundTrip(): void
    {
        $payload = ['ok' => true, 'count' => 2];

        $decoded = (new MixedChain($payload))
            ->jsonEncode()
            ->jsonDecode()
            ->get();

        self::assertSame($payload, $decoded);
    }
}
