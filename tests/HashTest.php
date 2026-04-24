<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class HashTest extends TestCase
{
    public function testHashDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Hash::class, 'of'));
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
        return [
            'hash' => [hash('sha256', 'payload'), Hash::hash('sha256', 'payload')],
            'hash_hmac' => [hash_hmac('sha256', 'payload', 'secret'), Hash::hashHmac('sha256', 'payload', 'secret')],
            'hash_equals_true' => [hash_equals('abc', 'abc'), Hash::hashEquals('abc', 'abc')],
            'md5' => [md5('payload'), Hash::md5('payload')],
            'sha1' => [sha1('payload'), Hash::sha1('payload')],
        ];
    }

    public function testPasswordHelpersMatchNativeBehavior(): void
    {
        $nativeHash = password_hash('secret', PASSWORD_BCRYPT);
        $wrappedHash = Hash::passwordHash('secret', PASSWORD_BCRYPT);

        self::assertIsString($nativeHash);
        self::assertIsString($wrappedHash);
        self::assertSame(password_verify('secret', $nativeHash), Hash::passwordVerify('secret', $nativeHash));
        self::assertSame(password_verify('secret', $wrappedHash), Hash::passwordVerify('secret', $wrappedHash));
        self::assertSame(
            password_needs_rehash($wrappedHash, PASSWORD_BCRYPT),
            Hash::passwordNeedsRehash($wrappedHash, PASSWORD_BCRYPT),
        );
    }

    public function testRandomHelpersReturnNativeShapes(): void
    {
        self::assertSame(strlen(random_bytes(16)), strlen(Hash::randomBytes(16)));

        $value = Hash::randomInt(10, 20);
        self::assertGreaterThanOrEqual(10, $value);
        self::assertLessThanOrEqual(20, $value);
    }
}
