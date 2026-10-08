<?php

declare(strict_types=1);

namespace Oophp;

final class Net
{
    public static function getHostByName(string $hostname): string
    {
        return gethostbyname($hostname);
    }

    public static function getHostByAddr(string $ipAddress): string|false
    {
        return gethostbyaddr($ipAddress);
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
}
