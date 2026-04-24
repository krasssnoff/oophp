<?php

declare(strict_types=1);

namespace Oophp;

final class Preg
{
    public static function pregMatch(
        string $pattern,
        string $subject,
        array &$matches = [],
        int $flags = 0,
        int $offset = 0,
    ): int|false {
        return preg_match($pattern, $subject, $matches, $flags, $offset);
    }

    public static function pregMatchAll(
        string $pattern,
        string $subject,
        array &$matches = [],
        int $flags = PREG_PATTERN_ORDER,
        int $offset = 0,
    ): int|false {
        return preg_match_all($pattern, $subject, $matches, $flags, $offset);
    }

    public static function pregReplace(
        array|string $pattern,
        array|string $replacement,
        array|string $subject,
        int $limit = -1,
        ?int &$count = null,
    ): string|array|null {
        return preg_replace($pattern, $replacement, $subject, $limit, $count);
    }

    public static function pregReplaceCallback(
        array|string $pattern,
        callable $callback,
        array|string $subject,
        int $limit = -1,
        ?int &$count = null,
        int $flags = 0,
    ): string|array|null {
        return preg_replace_callback($pattern, $callback, $subject, $limit, $count, $flags);
    }

    public static function pregSplit(string $pattern, string $subject, int $limit = -1, int $flags = 0): array|false
    {
        return preg_split($pattern, $subject, $limit, $flags);
    }

    public static function pregGrep(string $pattern, array $array, int $flags = 0): array|false
    {
        return preg_grep($pattern, $array, $flags);
    }

    public static function pregQuote(string $str, ?string $delimiter = null): string
    {
        return preg_quote($str, $delimiter);
    }

    public static function pregLastError(): int
    {
        return preg_last_error();
    }

    public static function pregLastErrorMessage(): string
    {
        return preg_last_error_msg();
    }
}
