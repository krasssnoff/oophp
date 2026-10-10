<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Chain\ArrayChain;
use Oophp\Chain\MixedChain;
use Oophp\Chain\NumberChain;
use Oophp\Str;
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

        self::assertEquals(json_decode($json), Json::decode($json));
        self::assertSame(json_decode($json, true), Json::decode($json, true));
    }

    public function testStaticValidateMatchesNativePhp(): void
    {
        $json = '{"ok":true,"count":2}';

        self::assertSame(json_validate($json), Json::validate($json));
    }

    public function testLastErrorAndMessageMatchNativePhpAfterInvalidDecode(): void
    {
        $invalidJson = '{"broken": }';

        json_decode($invalidJson);
        $expectedError = json_last_error();
        $expectedMessage = json_last_error_msg();

        Json::decode($invalidJson);
        $actualError = Json::lastError();
        $actualMessage = Json::lastErrorMsg();

        self::assertSame($expectedError, $actualError);
        self::assertSame($expectedMessage, $actualMessage);
    }

    public function testMixedChainJsonBridgeSupportsRoundTrip(): void
    {
        $payload = ['ok' => true, 'count' => 2];

        $decoded = (new MixedChain($payload))
            ->jsonEncode()
            ->jsonDecode(true)
            ->get();

        self::assertSame($payload, $decoded);
    }

    public function testChainJsonDecodeKeepsNativeDefaultAndLivesOnStringChains(): void
    {
        $json = '{"ok":true}';

        self::assertEquals(json_decode($json), Str::of($json)->jsonDecode()->get());
        self::assertInstanceOf(NumberChain::class, Str::of('42')->jsonDecode());
        self::assertFalse(method_exists(ArrayChain::class, 'jsonDecode'));
        self::assertFalse(method_exists(NumberChain::class, 'jsonDecode'));
    }
}
