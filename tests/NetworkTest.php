<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Network;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NetworkTest extends TestCase
{
    public function testNetworkDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Network::class, 'of'));
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
            'gethostbyname' => [gethostbyname('localhost'), Network::getHostByName('localhost')],
            'gethostbynamel' => [gethostbynamel('localhost'), Network::getHostByNameList('localhost')],
            'gethostbyaddr' => [gethostbyaddr('127.0.0.1'), Network::getHostByAddr('127.0.0.1')],
            'checkdnsrr' => [checkdnsrr('localhost', 'A'), Network::checkDns('localhost', 'A')],
            'ip2long' => [ip2long('127.0.0.1'), Network::ipToLong('127.0.0.1')],
            'long2ip' => [long2ip(2130706433), Network::longToIp(2130706433)],
        ];
    }

    public function testDnsGetRecordConformanceWithOutputArrays(): void
    {
        $nativeAuth = null;
        $nativeAdditional = null;
        $wrappedAuth = null;
        $wrappedAdditional = null;

        $expected = dns_get_record('localhost', DNS_A, $nativeAuth, $nativeAdditional, false);
        $actual = Network::dnsGetRecord('localhost', DNS_A, $wrappedAuth, $wrappedAdditional, false);

        self::assertSame($expected, $actual);
        self::assertSame($nativeAuth, $wrappedAuth);
        self::assertSame($nativeAdditional, $wrappedAdditional);
    }

    public function testGetMxRecordsConformanceWithOutputArrays(): void
    {
        $nativeHosts = [];
        $nativeWeights = [];
        $wrappedHosts = [];
        $wrappedWeights = [];

        $expected = getmxrr('localhost', $nativeHosts, $nativeWeights);
        $actual = Network::getMxRecords('localhost', $wrappedHosts, $wrappedWeights);

        self::assertSame($expected, $actual);
        self::assertSame($nativeHosts, $wrappedHosts);
        self::assertSame($nativeWeights, $wrappedWeights);
    }
}
