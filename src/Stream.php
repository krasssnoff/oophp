<?php

declare(strict_types=1);

namespace Oophp;

final class Stream
{
    public static function fopen(string $filename, string $mode, bool $useIncludePath = false, mixed $context = null): mixed
    {
        return fopen($filename, $mode, $useIncludePath, $context);
    }

    public static function fread(mixed $stream, int $length): string|false
    {
        return fread($stream, $length);
    }

    public static function fwrite(mixed $stream, string $data, ?int $length = null): int|false
    {
        return fwrite($stream, $data, $length);
    }

    public static function fclose(mixed $stream): bool
    {
        return fclose($stream);
    }

    public static function streamGetContents(mixed $stream, ?int $length = null, int $offset = -1): string|false
    {
        return stream_get_contents($stream, $length, $offset);
    }
}
