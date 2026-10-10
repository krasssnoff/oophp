<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\UrlChain;

final class Url
{
    private function __construct()
    {
    }

    public static function of(string $value): UrlChain
    {
        return new UrlChain($value);
    }

    public static function parse(string $url, int $component = -1): int|string|array|null|false
    {
        return parse_url($url, $component);
    }

    public static function rawEncode(string $string): string
    {
        return rawurlencode($string);
    }

    public static function rawDecode(string $string): string
    {
        return rawurldecode($string);
    }

    public static function encode(string $string): string
    {
        return urlencode($string);
    }

    public static function decode(string $string): string
    {
        return urldecode($string);
    }

    public static function httpBuildQuery(array|object $data, string $numericPrefix = '', ?string $argSeparator = null, int $encodingType = PHP_QUERY_RFC1738): string
    {
        return http_build_query($data, $numericPrefix, $argSeparator, $encodingType);
    }
}
