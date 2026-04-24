<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Enc;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EncTest extends TestCase
{
    public function testEncodingDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Enc::class, 'of'));
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
        $payload = ['ok' => true, 'count' => 2];
        $serialized = serialize($payload);
        $packed = pack('nvc*', 0x1234, 0x56, 0x78, 0x9A);

        return [
            'base64_encode' => [base64_encode('hello'), Enc::base64Encode('hello')],
            'base64_decode' => [base64_decode('aGVsbG8=', true), Enc::base64Decode('aGVsbG8=', true)],
            'bin2hex' => [bin2hex("A\0B"), Enc::bin2hex("A\0B")],
            'hex2bin' => [hex2bin('410042'), Enc::hex2bin('410042')],
            'pack' => [pack('nvc*', 0x1234, 0x56, 0x78, 0x9A), Enc::pack('nvc*', 0x1234, 0x56, 0x78, 0x9A)],
            'unpack' => [unpack('nfirst/csecond/c*rest', $packed), Enc::unpack('nfirst/csecond/c*rest', $packed)],
            'serialize' => [$serialized, Enc::serialize($payload)],
            'unserialize' => [unserialize($serialized, ['allowed_classes' => false]), Enc::unserialize($serialized, ['allowed_classes' => false])],
        ];
    }
}
