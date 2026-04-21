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
            'array_search' => self::entry('Arr', 'search', 'search', false, true, false, ['mixed-return']),
            'array_filter' => self::entry('Arr', 'filter', 'filter', false, false, false),
            'array_map' => self::entry('Arr', 'map', 'map', false, false, false),
            'array_reverse' => self::entry('Arr', 'reverse', 'reverse', false, false, false),
            'array_merge' => self::entry('Arr', 'merge', 'merge', false, false, false),
            'array_slice' => self::entry('Arr', 'slice', 'slice', false, false, false),
            'array_unique' => self::entry('Arr', 'unique', 'unique', false, false, false),
            'array_chunk' => self::entry('Arr', 'chunk', 'chunk', false, false, false),
            'array_flip' => self::entry('Arr', 'flip', 'flip', false, false, false),
            'array_pad' => self::entry('Arr', 'pad', 'pad', false, false, false),
            'array_combine' => self::entry('Arr', 'combine', 'combine', false, false, false),
            'array_merge_recursive' => self::entry('Arr', 'mergeRecursive', 'mergeRecursive', false, false, false),
            'array_column' => self::entry('Arr', 'column', 'column', false, false, false),
            'array_diff' => self::entry('Arr', 'diff', 'diff', false, false, false),
            'array_intersect' => self::entry('Arr', 'intersect', 'intersect', false, false, false),
            'array_replace' => self::entry('Arr', 'replace', 'replaceArray', false, false, false),
            'array_count_values' => self::entry('Arr', 'countValues', 'countValues', false, false, false),
            'in_array' => self::entry('Arr', 'inArray', 'inArray', false, false, false, ['bool-terminal']),
            'array_is_list' => self::entry('Arr', 'isList', 'isList', false, false, false, ['bool-terminal']),

            'str_replace' => self::entry('Str', 'replace', 'replace', false, false, false),
            'strtolower' => self::entry('Str', 'lower', 'lower', false, false, false),
            'strtoupper' => self::entry('Str', 'upper', 'upper', false, false, false),
            'trim' => self::entry('Str', 'trim', 'trim', false, false, false),
            'str_contains' => self::entry('Str', 'contains', 'contains', false, false, false, ['bool-terminal']),
            'explode' => self::entry('Str', 'split', 'split', false, false, false, ['type-handoff']),

            'json_encode' => self::entry('Json', 'encode', 'jsonEncode', false, true, false, ['mixed-return', 'type-handoff']),
            'json_decode' => self::entry('Json', 'decode', 'jsonDecode', false, true, false, ['mixed-return', 'type-handoff']),
            'json_validate' => self::entry('Json', 'validate', null, false, false, false, ['static-only']),
            'json_last_error' => self::entry('Json', 'lastError', null, false, false, false, ['ambient-state', 'static-only']),
            'json_last_error_msg' => self::entry('Json', 'lastErrorMessage', null, false, false, false, ['ambient-state', 'static-only']),

            'getenv' => self::entry('Sys', 'env', null, false, true, true, ['env-read', 'static-only']),
            'gethostname' => self::entry('Sys', 'hostname', null, false, true, true, ['sys-read', 'static-only']),
            'phpversion' => self::entry('Sys', 'version', null, false, true, true, ['sys-read', 'static-only']),
            'php_sapi_name' => self::entry('Sys', 'sapi', null, false, false, true, ['sys-read', 'static-only']),
            'php_uname' => self::entry('Sys', 'uname', null, false, false, true, ['sys-read', 'static-only']),
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
        bool $sideEffect,
        array $riskFlags = [],
    ): array {
        $riskFlags = array_values(array_unique($riskFlags));

        return [
            'class' => $class,
            'static_method' => $staticMethod,
            'chain_method' => $chainMethod,
            'chainable' => $chainMethod !== null,
            'by_reference' => $byReference,
            'mixed_return' => $mixedReturn,
            'side_effect' => $sideEffect,
            'risk_flags' => $riskFlags,
        ];
    }
}
