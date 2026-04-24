<?php

declare(strict_types=1);

namespace Oophp;

use Oophp\Chain\StringChain;
use Oophp\Chain\UrlChain;

final class Url
{
    public static function of(string $value): UrlChain
    {
        return new UrlChain($value);
    }

    public static function parse(string $url, int $component = -1): mixed
    {
        return parse_url($url, $component);
    }

    public static function buildQuery(
        array|object $data,
        string $numericPrefix = '',
        ?string $argSeparator = null,
        int $encodingType = PHP_QUERY_RFC1738,
    ): string {
        return http_build_query($data, $numericPrefix, $argSeparator, $encodingType);
    }

    public static function rawencode(string $string): string
    {
        return rawurlencode($string);
    }

    public static function rawdecode(string $string): string
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

    public static function query(array|object $data, string $numericPrefix = '', ?string $argSeparator = null, int $encodingType = PHP_QUERY_RFC1738): StringChain
    {
        return new StringChain(self::buildQuery($data, $numericPrefix, $argSeparator, $encodingType));
    }
}
