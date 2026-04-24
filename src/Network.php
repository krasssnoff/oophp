<?php

declare(strict_types=1);

namespace Oophp;

final class Network
{
    public static function getHostByName(string $hostname): string
    {
        return gethostbyname($hostname);
    }

    public static function getHostByNameList(string $hostname): array|false
    {
        return gethostbynamel($hostname);
    }

    public static function getHostByAddr(string $ipAddress): string|false
    {
        return gethostbyaddr($ipAddress);
    }

    public static function checkDns(string $hostname, string $recordType = 'MX'): bool
    {
        return checkdnsrr($hostname, $recordType);
    }

    public static function dnsGetRecord(
        string $hostname,
        int $type = DNS_ANY,
        ?array &$authoritativeNameServers = null,
        ?array &$additionalRecords = null,
        bool $raw = false,
    ): array|false {
        return dns_get_record($hostname, $type, $authoritativeNameServers, $additionalRecords, $raw);
    }

    public static function getMxRecords(string $hostname, array &$mxhosts, array &$weights = []): bool
    {
        return getmxrr($hostname, $mxhosts, $weights);
    }

    public static function ipToLong(string $ipAddress): int|false
    {
        return ip2long($ipAddress);
    }

    public static function longToIp(int $ipAddress): string|false
    {
        return long2ip($ipAddress);
    }
}
