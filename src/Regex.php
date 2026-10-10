<?php

declare(strict_types=1);

namespace Oophp;

final class Regex
{
    private function __construct()
    {
    }

    /**
     * @param int-mask-of<PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL> $flags
     */
    public static function match(
        string $pattern,
        string $subject,
        mixed &$matches = null,
        int $flags = 0,
        int $offset = 0,
    ): int|false {
        return preg_match($pattern, $subject, $matches, $flags, $offset);
    }

    public static function matchAll(
        string $pattern,
        string $subject,
        mixed &$matches = null,
        int $flags = 0,
        int $offset = 0,
    ): int|false {
        return preg_match_all($pattern, $subject, $matches, $flags, $offset);
    }

    public static function replace(
        array|string $pattern,
        array|string $replacement,
        array|string $subject,
        int $limit = -1,
        mixed &$count = null,
    ): string|array|null {
        return preg_replace($pattern, $replacement, $subject, $limit, $count);
    }

    public static function replaceCallback(
        array|string $pattern,
        callable $callback,
        array|string $subject,
        int $limit = -1,
        mixed &$count = null,
        int $flags = 0,
    ): string|array|null {
        return preg_replace_callback($pattern, $callback, $subject, $limit, $count, $flags);
    }

    public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0): array|false
    {
        return preg_split($pattern, $subject, $limit, $flags);
    }

    public static function grep(string $pattern, array $array, int $flags = 0): array|false
    {
        return preg_grep($pattern, $array, $flags);
    }

    public static function quote(string $str, ?string $delimiter = null): string
    {
        return preg_quote($str, $delimiter);
    }

    public static function lastError(): int
    {
        return preg_last_error();
    }

    public static function lastErrorMsg(): string
    {
        return preg_last_error_msg();
    }
}
