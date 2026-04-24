<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Stream;
use PHPUnit\Framework\TestCase;

final class StreamTest extends TestCase
{
    public function testStreamDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Stream::class, 'of'));
    }

    public function testTmpFileStreamLifecycleMatchesNativePhp(): void
    {
        $base = sys_get_temp_dir() . '/oophp-stream-' . uniqid('', true);
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
}
