<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Arr;
use Oophp\Encoding;
use Oophp\Fs;
use Oophp\Hash;
use Oophp\Json;
use Oophp\Math;
use Oophp\MbStr;
use Oophp\Path;
use Oophp\Preg;
use Oophp\Stream;
use Oophp\Str;
use Oophp\Time;
use Oophp\Url;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConformanceTest extends TestCase
{
    #[DataProvider('arrStaticProvider')]
    public function testArrStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function arrStaticProvider(): array
    {
        $source = ['x' => 10, 'y' => 20];

        return [
            'values' => [array_values($source), Arr::values($source)],
            'keys' => [array_keys($source), Arr::keys($source)],
            'search_strict_false' => [array_search('20', [10, 20], false), Arr::search('20', [10, 20], false)],
            'search_strict_true' => [array_search('20', [10, 20], true), Arr::search('20', [10, 20], true)],
            'reverse_preserve' => [array_reverse($source, true), Arr::reverse($source, true)],
            'merge' => [array_merge(['a' => 1], ['b' => 2]), Arr::merge(['a' => 1], ['b' => 2])],
            'slice_preserve_keys' => [array_slice($source, 1, 1, true), Arr::slice($source, 1, 1, true)],
            'unique' => [array_unique(['x', 'x', 'y'], SORT_STRING), Arr::unique(['x', 'x', 'y'])],
            'chunk' => [array_chunk($source, 1, true), Arr::chunk($source, 1, true)],
            'flip' => [array_flip(['x' => 'alpha', 'y' => 'beta']), Arr::flip(['x' => 'alpha', 'y' => 'beta'])],
            'pad' => [array_pad([1, 2], 4, 0), Arr::pad([1, 2], 4, 0)],
            'combine' => [array_combine(['id', 'name'], [10, 'Ann']), Arr::combine(['id', 'name'], [10, 'Ann'])],
            'merge_recursive' => [array_merge_recursive(['k' => ['a']], ['k' => ['b']]), Arr::mergeRecursive(['k' => ['a']], ['k' => ['b']])],
            'column_with_index' => [
                array_column([['id' => 1, 'name' => 'a'], ['id' => 2, 'name' => 'b']], 'name', 'id'),
                Arr::column([['id' => 1, 'name' => 'a'], ['id' => 2, 'name' => 'b']], 'name', 'id'),
            ],
            'diff' => [array_diff(['a', 'b', 'c'], ['b']), Arr::diff(['a', 'b', 'c'], ['b'])],
            'intersect' => [array_intersect(['a', 'b', 'c'], ['b', 'd']), Arr::intersect(['a', 'b', 'c'], ['b', 'd'])],
            'replace' => [array_replace(['x' => 1, 'y' => 2], ['y' => 20]), Arr::replace(['x' => 1, 'y' => 2], ['y' => 20])],
            'count_values' => [array_count_values(['a', 'b', 'a']), Arr::countValues(['a', 'b', 'a'])],
            'in_array_strict_false' => [in_array('2', [1, 2], false), Arr::inArray('2', [1, 2], false)],
            'is_list_true' => [array_is_list([10, 20, 30]), Arr::isList([10, 20, 30])],
            'change_key_case_upper' => [array_change_key_case(['first' => 1], CASE_UPPER), Arr::changeKeyCase(['first' => 1], CASE_UPPER)],
            'fill_keys' => [array_fill_keys(['id', 'name'], 0), Arr::fillKeys(['id', 'name'], 0)],
            'key_first' => [array_key_first(['b' => 2, 'a' => 1]), Arr::keyFirst(['b' => 2, 'a' => 1])],
            'key_last' => [array_key_last(['b' => 2, 'a' => 1]), Arr::keyLast(['b' => 2, 'a' => 1])],
            'diff_assoc' => [array_diff_assoc(['a' => 1, 'b' => 2], ['a' => 1]), Arr::diffAssoc(['a' => 1, 'b' => 2], ['a' => 1])],
            'diff_key' => [array_diff_key(['a' => 1, 'b' => 2], ['a' => 9]), Arr::diffKey(['a' => 1, 'b' => 2], ['a' => 9])],
            'intersect_assoc' => [array_intersect_assoc(['a' => 1, 'b' => 2], ['b' => 2, 'c' => 3]), Arr::intersectAssoc(['a' => 1, 'b' => 2], ['b' => 2, 'c' => 3])],
            'intersect_key' => [array_intersect_key(['a' => 1, 'b' => 2], ['b' => 9]), Arr::intersectKey(['a' => 1, 'b' => 2], ['b' => 9])],
            'replace_recursive' => [
                array_replace_recursive(['cfg' => ['a' => 1]], ['cfg' => ['b' => 2]]),
                Arr::replaceRecursive(['cfg' => ['a' => 1]], ['cfg' => ['b' => 2]]),
            ],
            'sum' => [array_sum([1, 2, 3]), Arr::sum([1, 2, 3])],
            'product' => [array_product([1.5, 2, 3]), Arr::product([1.5, 2, 3])],
            'key_exists' => [array_key_exists('a', ['a' => 1]), Arr::keyExists('a', ['a' => 1])],
        ];
    }

    #[DataProvider('strStaticProvider')]
    public function testStrStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function strStaticProvider(): array
    {
        return [
            'replace' => [str_replace('a', 'b', 'a-cat'), Str::replace('a', 'b', 'a-cat')],
            'tolower' => [strtolower('TeSt'), Str::tolower('TeSt')],
            'toupper' => [strtoupper('TeSt'), Str::toupper('TeSt')],
            'trim' => [trim('  test  '), Str::trim('  test  ')],
            'contains_true' => [str_contains('package', 'ack'), Str::contains('package', 'ack')],
            'starts_with' => [str_starts_with('package', 'pack'), Str::startsWith('package', 'pack')],
            'ends_with' => [str_ends_with('package', 'age'), Str::endsWith('package', 'age')],
            'len' => [strlen('TeSt'), Str::len('TeSt')],
            'pos_with_offset' => [strpos('banana', 'na', 3), Str::pos('banana', 'na', 3)],
            'ipos_with_offset' => [stripos('BaNaNa', 'na', 3), Str::ipos('BaNaNa', 'na', 3)],
            'rpos' => [strrpos('banana', 'na'), Str::rpos('banana', 'na')],
            'ripos' => [strripos('BaNaNa', 'NA'), Str::ripos('BaNaNa', 'NA')],
            'repeat' => [str_repeat('ab', 3), Str::repeat('ab', 3)],
            'rev' => [strrev('desserts'), Str::rev('desserts')],
            'substr' => [substr('package', 1, 3), Str::substr('package', 1, 3)],
            'substr_count' => [substr_count('banana', 'na', 1, 4), Str::substrCount('banana', 'na', 1, 4)],
            'substr_replace' => [substr_replace('abcdef', 'X', 2, 3), Str::substrReplace('abcdef', 'X', 2, 3)],
            'split_limit' => [explode(',', 'a,b,c', 2), Str::split(',', 'a,b,c', 2)],
        ];
    }

    #[DataProvider('jsonStaticProvider')]
    public function testJsonStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function jsonStaticProvider(): array
    {
        $payload = ['ok' => true, 'id' => 1];
        $encoded = '{"ok":true,"id":1}';

        return [
            'encode' => [json_encode($payload, 0, 512), Json::encode($payload)],
            'decode' => [json_decode($encoded, true, 512, 0), Json::decode($encoded)],
            'validate' => [json_validate($encoded), Json::validate($encoded)],
        ];
    }

    public function testJsonErrorStateConformanceAfterInvalidDecode(): void
    {
        $invalidJson = '{"broken": }';

        json_decode($invalidJson, true, 512, 0);
        $expectedError = json_last_error();
        $expectedMessage = json_last_error_msg();

        Json::decode($invalidJson);
        $actualError = Json::lastError();
        $actualMessage = Json::lastErrorMessage();

        self::assertSame($expectedError, $actualError);
        self::assertSame($expectedMessage, $actualMessage);
    }

    #[DataProvider('mbStrStaticProvider')]
    public function testMbStrStaticConformance(mixed $expected, mixed $actual): void
    {
        if (!function_exists('mb_strlen')) {
            self::markTestSkipped('mbstring extension is not available.');
        }

        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function mbStrStaticProvider(): array
    {
        if (!function_exists('mb_strlen')) {
            return [
                'mbstring_unavailable' => [null, null],
            ];
        }

        return [
            'tolower' => [mb_strtolower('ПрИвЕт', 'UTF-8'), MbStr::tolower('ПрИвЕт', 'UTF-8')],
            'toupper' => [mb_strtoupper('ПрИвЕт', 'UTF-8'), MbStr::toupper('ПрИвЕт', 'UTF-8')],
            'len' => [mb_strlen('Привет', 'UTF-8'), MbStr::len('Привет', 'UTF-8')],
            'pos' => [mb_strpos('До свидания', 'вид', 0, 'UTF-8'), MbStr::pos('До свидания', 'вид', 0, 'UTF-8')],
            'rpos' => [mb_strrpos('абв абв', 'абв', 0, 'UTF-8'), MbStr::rpos('абв абв', 'абв', 0, 'UTF-8')],
            'substr' => [mb_substr('Привет', 1, 3, 'UTF-8'), MbStr::substr('Привет', 1, 3, 'UTF-8')],
            'split' => [mb_str_split('Привет', 2, 'UTF-8'), MbStr::split('Привет', 2, 'UTF-8')],
        ];
    }

    #[DataProvider('mathStaticProvider')]
    public function testMathStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function mathStaticProvider(): array
    {
        return [
            'abs' => [abs(-8.5), Math::abs(-8.5)],
            'ceil' => [ceil(2.1), Math::ceil(2.1)],
            'floor' => [floor(2.9), Math::floor(2.9)],
            'round' => [round(2.55, 1, PHP_ROUND_HALF_UP), Math::round(2.55, 1, PHP_ROUND_HALF_UP)],
            'max' => [max(10, 2, 7), Math::max(10, 2, 7)],
            'min' => [min(10, 2, 7), Math::min(10, 2, 7)],
            'pow' => [pow(3, 4), Math::pow(3, 4)],
            'sqrt' => [sqrt(49), Math::sqrt(49)],
            'fmod' => [fmod(5.7, 1.3), Math::fmod(5.7, 1.3)],
            'intdiv' => [intdiv(20, 3), Math::intdiv(20, 3)],
        ];
    }

    #[DataProvider('urlStaticProvider')]
    public function testUrlStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function urlStaticProvider(): array
    {
        return [
            'parse' => [parse_url('https://example.com/path?q=1#frag'), Url::parse('https://example.com/path?q=1#frag')],
            'parse_host' => [
                parse_url('https://example.com/path?q=1#frag', PHP_URL_HOST),
                Url::parse('https://example.com/path?q=1#frag', PHP_URL_HOST),
            ],
            'build_query' => [
                http_build_query(['a b' => 'x y'], '', '&', PHP_QUERY_RFC3986),
                Url::buildQuery(['a b' => 'x y'], '', '&', PHP_QUERY_RFC3986),
            ],
            'rawencode' => [rawurlencode('a b/c'), Url::rawencode('a b/c')],
            'rawdecode' => [rawurldecode('a%20b%2Fc'), Url::rawdecode('a%20b%2Fc')],
            'encode' => [urlencode('a b/c'), Url::encode('a b/c')],
            'decode' => [urldecode('a+b%2Fc'), Url::decode('a+b%2Fc')],
        ];
    }

    #[DataProvider('encodingStaticProvider')]
    public function testEncodingStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function encodingStaticProvider(): array
    {
        $payload = ['ok' => true, 'count' => 2];
        $serialized = serialize($payload);
        $packed = pack('nvc*', 0x1234, 0x56, 0x78, 0x9A);

        return [
            'base64_encode' => [base64_encode('hello'), Encoding::base64Encode('hello')],
            'base64_decode_strict' => [base64_decode('aGVsbG8=', true), Encoding::base64Decode('aGVsbG8=', true)],
            'bin2hex' => [bin2hex("A\0B"), Encoding::bin2hex("A\0B")],
            'hex2bin' => [hex2bin('410042'), Encoding::hex2bin('410042')],
            'pack' => [pack('nvc*', 0x1234, 0x56, 0x78, 0x9A), Encoding::pack('nvc*', 0x1234, 0x56, 0x78, 0x9A)],
            'unpack' => [unpack('nfirst/csecond/c*rest', $packed), Encoding::unpack('nfirst/csecond/c*rest', $packed)],
            'serialize' => [$serialized, Encoding::serialize($payload)],
            'unserialize' => [
                unserialize($serialized, ['allowed_classes' => false]),
                Encoding::unserialize($serialized, ['allowed_classes' => false]),
            ],
        ];
    }

    #[DataProvider('pregStaticProvider')]
    public function testPregStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function pregStaticProvider(): array
    {
        return [
            'preg_replace' => [
                preg_replace('/\s+/', '-', 'hello world'),
                Preg::pregReplace('/\s+/', '-', 'hello world'),
            ],
            'preg_split' => [
                preg_split('/\s*,\s*/', 'a, b, c', -1, PREG_SPLIT_NO_EMPTY),
                Preg::pregSplit('/\s*,\s*/', 'a, b, c', -1, PREG_SPLIT_NO_EMPTY),
            ],
            'preg_grep' => [
                preg_grep('/^a/u', ['alpha', 'beta', 'axis']),
                Preg::pregGrep('/^a/u', ['alpha', 'beta', 'axis']),
            ],
            'preg_quote' => [
                preg_quote('a+b?c', '/'),
                Preg::pregQuote('a+b?c', '/'),
            ],
            'preg_replace_callback' => [
                preg_replace_callback('/(\d+)/', static fn (array $m): string => '[' . $m[1] . ']', 'id=42'),
                Preg::pregReplaceCallback('/(\d+)/', static fn (array $m): string => '[' . $m[1] . ']', 'id=42'),
            ],
        ];
    }

    public function testPregMatchOutputConformance(): void
    {
        $expectedMatches = [];
        $actualMatches = [];

        $expected = preg_match('/(\w+)-(\d+)/', 'item-42', $expectedMatches);
        $actual = Preg::pregMatch('/(\w+)-(\d+)/', 'item-42', $actualMatches);

        self::assertSame($expected, $actual);
        self::assertSame($expectedMatches, $actualMatches);
    }

    public function testPregMatchAllOutputConformance(): void
    {
        $expectedMatches = [];
        $actualMatches = [];

        $expected = preg_match_all('/(\w+)/', 'alpha beta', $expectedMatches, PREG_PATTERN_ORDER);
        $actual = Preg::pregMatchAll('/(\w+)/', 'alpha beta', $actualMatches, PREG_PATTERN_ORDER);

        self::assertSame($expected, $actual);
        self::assertSame($expectedMatches, $actualMatches);
    }

    #[DataProvider('pathStaticProvider')]
    public function testPathStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function pathStaticProvider(): array
    {
        $samplePath = '/var/www/app/archive.tar.gz';
        $existingPath = __FILE__;

        return [
            'basename' => [basename($samplePath), Path::basename($samplePath)],
            'basename_suffix' => [basename($samplePath, '.gz'), Path::basename($samplePath, '.gz')],
            'dirname' => [dirname($samplePath), Path::dirname($samplePath)],
            'dirname_levels' => [dirname($samplePath, 2), Path::dirname($samplePath, 2)],
            'pathinfo' => [pathinfo($samplePath), Path::pathinfo($samplePath)],
            'pathinfo_extension' => [pathinfo($samplePath, PATHINFO_EXTENSION), Path::pathinfo($samplePath, PATHINFO_EXTENSION)],
            'realpath' => [realpath($existingPath), Path::realpath($existingPath)],
        ];
    }

    public function testFsTmpLifecycleConformance(): void
    {
        $base = sys_get_temp_dir() . '/oophp-fs-conformance-' . uniqid('', true);
        $nativeRoot = $base . '-native';
        $wrappedRoot = $base . '-wrapped';

        $nativeSource = $nativeRoot . '/source.txt';
        $nativeCopy = $nativeRoot . '/copy.txt';
        $nativeRenamed = $nativeRoot . '/renamed.txt';

        $wrappedSource = $wrappedRoot . '/source.txt';
        $wrappedCopy = $wrappedRoot . '/copy.txt';
        $wrappedRenamed = $wrappedRoot . '/renamed.txt';

        try {
            self::assertSame(mkdir($nativeRoot, 0777, true), Fs::mkdir($wrappedRoot, 0777, true));
            self::assertSame(file_put_contents($nativeSource, "line-1\nline-2"), Fs::filePutContents($wrappedSource, "line-1\nline-2"));
            self::assertSame(file_exists($nativeSource), Fs::fileExists($wrappedSource));
            self::assertSame(file_get_contents($nativeSource), Fs::fileGetContents($wrappedSource));
            self::assertSame(copy($nativeSource, $nativeCopy), Fs::copy($wrappedSource, $wrappedCopy));
            self::assertSame(rename($nativeCopy, $nativeRenamed), Fs::rename($wrappedCopy, $wrappedRenamed));

            $nativeGlob = glob($nativeRoot . '/*.txt');
            $wrappedGlob = Fs::glob($wrappedRoot . '/*.txt');
            if (is_array($nativeGlob)) {
                $nativeGlob = array_map(static fn (string $path): string => basename($path), $nativeGlob);
                sort($nativeGlob);
            }
            if (is_array($wrappedGlob)) {
                $wrappedGlob = array_map(static fn (string $path): string => basename($path), $wrappedGlob);
                sort($wrappedGlob);
            }

            self::assertSame($nativeGlob, $wrappedGlob);
            self::assertSame(scandir($nativeRoot), Fs::scandir($wrappedRoot));
            self::assertSame(unlink($nativeRenamed), Fs::unlink($wrappedRenamed));
            self::assertSame(unlink($nativeSource), Fs::unlink($wrappedSource));
        } finally {
            @unlink($nativeRenamed);
            @unlink($nativeCopy);
            @unlink($nativeSource);
            @rmdir($nativeRoot);

            @unlink($wrappedRenamed);
            @unlink($wrappedCopy);
            @unlink($wrappedSource);
            @rmdir($wrappedRoot);
        }
    }

    public function testStreamResourceLifecycleConformance(): void
    {
        $base = sys_get_temp_dir() . '/oophp-stream-conformance-' . uniqid('', true);
        $nativePath = $base . '-native.txt';
        $wrappedPath = $base . '-wrapped.txt';

        $native = null;
        $wrapped = null;

        try {
            $native = fopen($nativePath, 'w+');
            $wrapped = Stream::fopen($wrappedPath, 'w+');

            self::assertIsResource($native);
            self::assertIsResource($wrapped);
            self::assertSame(fwrite($native, "alpha\nbeta"), Stream::fwrite($wrapped, "alpha\nbeta"));

            rewind($native);
            rewind($wrapped);
            self::assertSame(stream_get_contents($native), Stream::streamGetContents($wrapped));

            rewind($native);
            rewind($wrapped);
            self::assertSame(fread($native, 5), Stream::fread($wrapped, 5));

            self::assertSame(fclose($native), Stream::fclose($wrapped));
            $native = null;
            $wrapped = null;
        } finally {
            if (is_resource($native)) {
                @fclose($native);
            }

            if (is_resource($wrapped)) {
                @fclose($wrapped);
            }

            @unlink($nativePath);
            @unlink($wrappedPath);
        }
    }

    #[DataProvider('timeStaticProvider')]
    public function testTimeStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function timeStaticProvider(): array
    {
        $timestamp = 1_704_067_200;

        return [
            'date' => [date('Y-m-d', $timestamp), Time::date('Y-m-d', $timestamp)],
            'gmdate' => [gmdate('Y-m-d H:i:s', $timestamp), Time::gmdate('Y-m-d H:i:s', $timestamp)],
            'strtotime' => [strtotime('+2 days', $timestamp), Time::strtotime('+2 days', $timestamp)],
            'mktime' => [mktime(12, 30, 15, 5, 10, 2024), Time::mktime(12, 30, 15, 5, 10, 2024)],
        ];
    }

    public function testTimeTimezoneConformance(): void
    {
        $original = date_default_timezone_get();

        try {
            self::assertSame(date_default_timezone_set('UTC'), Time::timezoneSet('UTC'));
            self::assertSame(date_default_timezone_get(), Time::timezoneGet());
        } finally {
            date_default_timezone_set($original);
        }
    }

    #[DataProvider('hashStaticProvider')]
    public function testHashStaticConformance(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function hashStaticProvider(): array
    {
        return [
            'hash' => [hash('sha256', 'payload'), Hash::hash('sha256', 'payload')],
            'hash_hmac' => [hash_hmac('sha256', 'payload', 'secret'), Hash::hashHmac('sha256', 'payload', 'secret')],
            'hash_equals' => [hash_equals('abc', 'abc'), Hash::hashEquals('abc', 'abc')],
            'md5' => [md5('payload'), Hash::md5('payload')],
            'sha1' => [sha1('payload'), Hash::sha1('payload')],
        ];
    }

    public function testFluentConformanceAcrossTypeHandoff(): void
    {
        $input = '  alpha,beta,gamma  ';
        $expected = array_search('beta', array_values(explode(',', strtolower(trim($input)))), false);

        $actual = Str::of($input)
            ->trim()
            ->tolower()
            ->split(',')
            ->values()
            ->search('beta')
            ->get();

        self::assertSame($expected, $actual);
    }

    public function testInvokeAndGetReturnSameTerminalValue(): void
    {
        $chain = Arr::of(['a' => 'x', 'b' => 'y'])->values()->search('y');

        self::assertSame($chain->get(), $chain());
    }
}
