<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Net;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NetTest extends TestCase
{
    public function testNetworkDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Net::class, 'of'));
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
            'gethostbyname' => [gethostbyname('localhost'), Net::getHostByName('localhost')],
            'gethostbyaddr' => [gethostbyaddr('127.0.0.1'), Net::getHostByAddr('127.0.0.1')],
        ];
    }

    public function testDnsGetRecordConformanceWithOutputArrays(): void
    {
        $nativeAuth = null;
        $nativeAdditional = null;
        $wrappedAuth = null;
        $wrappedAdditional = null;

        $expected = dns_get_record('localhost', DNS_A, $nativeAuth, $nativeAdditional, false);
        $actual = Net::dnsGetRecord('localhost', DNS_A, $wrappedAuth, $wrappedAdditional, false);

        self::assertSame($expected, $actual);
        self::assertSame($nativeAuth, $wrappedAuth);
        self::assertSame($nativeAdditional, $wrappedAdditional);
    }
}
