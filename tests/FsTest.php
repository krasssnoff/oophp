<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Fs;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FsTest extends TestCase
{
    public function testFsDomainRemainsStaticOnly(): void
    {
        self::assertTrue(method_exists(Fs::class, 'of'));
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

    #[DataProvider('pathStaticProvider')]
    public function testPathHelpersMatchNativePhp(mixed $expected, mixed $actual): void
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
            'basename' => [basename($samplePath), Fs::basename($samplePath)],
            'basename_with_suffix' => [basename($samplePath, '.gz'), Fs::basename($samplePath, '.gz')],
            'dirname' => [dirname($samplePath), Fs::dirname($samplePath)],
            'dirname_levels' => [dirname($samplePath, 2), Fs::dirname($samplePath, 2)],
            'pathinfo_all' => [pathinfo($samplePath), Fs::pathinfo($samplePath)],
            'pathinfo_extension' => [pathinfo($samplePath, PATHINFO_EXTENSION), Fs::pathinfo($samplePath, PATHINFO_EXTENSION)],
            'realpath_existing' => [realpath($existingPath), Fs::realpath($existingPath)],
        ];
    }

    public function testRealpathMissingPathMatchesNativePhp(): void
    {
        $missing = '/tmp/oophp-not-existing-path-' . uniqid('', true);

        self::assertSame(realpath($missing), Fs::realpath($missing));
    }

    public function testFsFluentWorkflowFromPathToStream(): void
    {
        $base = sys_get_temp_dir() . '/oophp-fs-fluent-' . uniqid('', true);
        $source = $base . '.txt';
        $copy = $base . '-copy.txt';
        $renamed = $base . '-renamed.txt';

        try {
            $chain = Fs::of($source)->normalize();
            self::assertSame(str_replace('\\', '/', $source), $chain->get());
            self::assertSame(basename($source), $chain->basename()->get());

            self::assertSame(file_put_contents($source, "alpha\nbeta"), $chain->write("alpha\nbeta")->get());
            self::assertSame(file_exists($source), $chain->exists()->get());
            self::assertSame(file_get_contents($source), $chain->read()->get());

            $copied = $chain->copyTo($copy);
            self::assertSame($copy, $copied->get());
            self::assertSame(file_exists($copy), $copied->exists()->get());

            $renamedChain = $copied->renameTo($renamed);
            self::assertSame($renamed, $renamedChain->get());

            $stream = $renamedChain->stream('r');
            $native = fopen($renamed, 'r');
            self::assertIsResource($native);
            self::assertSame(stream_get_contents($native), $stream->contents()->get());
            fclose($native);
            self::assertTrue($stream->close()->get());

            self::assertTrue($chain->delete()->get());
            self::assertTrue($renamedChain->delete()->get());
        } finally {
            @unlink($source);
            @unlink($copy);
            @unlink($renamed);
        }
    }
}
