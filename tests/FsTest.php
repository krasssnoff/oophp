<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Fs;
use PHPUnit\Framework\TestCase;

final class FsTest extends TestCase
{
    public function testFsDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Fs::class, 'of'));
    }

    public function testTmpLifecycleMatchesNativePhpBehavior(): void
    {
        $base = sys_get_temp_dir() . '/oophp-fs-' . uniqid('', true);
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

            $nativeScandir = scandir($nativeRoot);
            $wrappedScandir = Fs::scandir($wrappedRoot);
            self::assertSame($nativeScandir, $wrappedScandir);

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
}
