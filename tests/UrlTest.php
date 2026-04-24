<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Url;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
    public function testUrlDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Url::class, 'of'));
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
            'parse_full' => [
                parse_url('https://example.com/path?q=1#frag'),
                Url::parse('https://example.com/path?q=1#frag'),
            ],
            'parse_host_component' => [
                parse_url('https://example.com/path?q=1#frag', PHP_URL_HOST),
                Url::parse('https://example.com/path?q=1#frag', PHP_URL_HOST),
            ],
            'build_query_rfc1738' => [
                http_build_query(['a b' => 'x y'], '', '&', PHP_QUERY_RFC1738),
                Url::buildQuery(['a b' => 'x y'], '', '&', PHP_QUERY_RFC1738),
            ],
            'build_query_rfc3986' => [
                http_build_query(['a b' => 'x y'], '', '&', PHP_QUERY_RFC3986),
                Url::buildQuery(['a b' => 'x y'], '', '&', PHP_QUERY_RFC3986),
            ],
            'rawencode' => [rawurlencode('a b/c'), Url::rawencode('a b/c')],
            'rawdecode' => [rawurldecode('a%20b%2Fc'), Url::rawdecode('a%20b%2Fc')],
            'encode' => [urlencode('a b/c'), Url::encode('a b/c')],
            'decode' => [urldecode('a+b%2Fc'), Url::decode('a+b%2Fc')],
        ];
    }
}
