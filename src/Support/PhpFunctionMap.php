<?php

declare(strict_types=1);

namespace Oophp\Support;

final class PhpFunctionMap
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function definitions(): array
    {
        return [
            'array_values' => self::entry('Arr', 'values', 'values', false, false, false),
            'array_keys' => self::entry('Arr', 'keys', 'keys', false, false, false),
            'array_search' => self::entry('Arr', 'search', 'search', false, true, false),
            'array_filter' => self::entry('Arr', 'filter', 'filter', false, false, false),
            'array_map' => self::entry('Arr', 'map', 'map', false, false, false),
            'array_reverse' => self::entry('Arr', 'reverse', 'reverse', false, false, false),

            'str_replace' => self::entry('Str', 'replace', 'replace', false, false, false),
            'strtolower' => self::entry('Str', 'lower', 'lower', false, false, false),
            'strtoupper' => self::entry('Str', 'upper', 'upper', false, false, false),
            'trim' => self::entry('Str', 'trim', 'trim', false, false, false),
            'str_contains' => self::entry('Str', 'contains', 'contains', false, false, false),
            'explode' => self::entry('Str', 'split', 'split', false, false, false),

            'json_encode' => self::entry('Json', 'encode', 'jsonEncode', false, true, false),
            'json_decode' => self::entry('Json', 'decode', 'jsonDecode', false, true, false),
            'json_validate' => self::entry('Json', 'validate', null, false, false, false),
            'json_last_error' => self::entry('Json', 'lastError', null, false, false, false),
            'json_last_error_msg' => self::entry('Json', 'lastErrorMessage', null, false, false, false),

            'getenv' => self::entry('Sys', 'env', null, false, true, true),
            'gethostname' => self::entry('Sys', 'hostname', null, false, true, true),
            'phpversion' => self::entry('Sys', 'version', null, false, true, true),
            'php_sapi_name' => self::entry('Sys', 'sapi', null, false, false, true),
            'php_uname' => self::entry('Sys', 'uname', null, false, false, true),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function entry(
        string $class,
        string $staticMethod,
        ?string $chainMethod,
        bool $byReference,
        bool $mixedReturn,
        bool $sideEffect
    ): array {
        return [
            'class' => $class,
            'static_method' => $staticMethod,
            'chain_method' => $chainMethod,
            'by_reference' => $byReference,
            'mixed_return' => $mixedReturn,
            'side_effect' => $sideEffect,
        ];
    }
}
