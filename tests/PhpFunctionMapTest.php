<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Support\PhpFunctionMap;
use PHPUnit\Framework\TestCase;

final class PhpFunctionMapTest extends TestCase
{
    public function testDefinitionsFreezeTheCurrentV1Surface(): void
    {
        self::assertSame(
            [
                'array_values' => ['class' => 'Arr', 'static_method' => 'values', 'chain_method' => 'values', 'risk_flags' => []],
                'array_keys' => ['class' => 'Arr', 'static_method' => 'keys', 'chain_method' => 'keys', 'risk_flags' => []],
                'array_search' => ['class' => 'Arr', 'static_method' => 'search', 'chain_method' => 'search', 'risk_flags' => ['mixed-return']],
                'array_filter' => ['class' => 'Arr', 'static_method' => 'filter', 'chain_method' => 'filter', 'risk_flags' => []],
                'array_map' => ['class' => 'Arr', 'static_method' => 'map', 'chain_method' => 'map', 'risk_flags' => []],
                'array_reverse' => ['class' => 'Arr', 'static_method' => 'reverse', 'chain_method' => 'reverse', 'risk_flags' => []],
                'array_merge' => ['class' => 'Arr', 'static_method' => 'merge', 'chain_method' => 'merge', 'risk_flags' => []],
                'array_slice' => ['class' => 'Arr', 'static_method' => 'slice', 'chain_method' => 'slice', 'risk_flags' => []],
                'array_unique' => ['class' => 'Arr', 'static_method' => 'unique', 'chain_method' => 'unique', 'risk_flags' => []],
                'array_chunk' => ['class' => 'Arr', 'static_method' => 'chunk', 'chain_method' => 'chunk', 'risk_flags' => []],
                'array_flip' => ['class' => 'Arr', 'static_method' => 'flip', 'chain_method' => 'flip', 'risk_flags' => []],
                'array_pad' => ['class' => 'Arr', 'static_method' => 'pad', 'chain_method' => 'pad', 'risk_flags' => []],
                'array_combine' => ['class' => 'Arr', 'static_method' => 'combine', 'chain_method' => 'combine', 'risk_flags' => []],
                'array_merge_recursive' => ['class' => 'Arr', 'static_method' => 'mergeRecursive', 'chain_method' => 'mergeRecursive', 'risk_flags' => []],
                'array_column' => ['class' => 'Arr', 'static_method' => 'column', 'chain_method' => 'column', 'risk_flags' => []],
                'array_diff' => ['class' => 'Arr', 'static_method' => 'diff', 'chain_method' => 'diff', 'risk_flags' => []],
                'array_intersect' => ['class' => 'Arr', 'static_method' => 'intersect', 'chain_method' => 'intersect', 'risk_flags' => []],
                'array_replace' => ['class' => 'Arr', 'static_method' => 'replace', 'chain_method' => 'replaceArray', 'risk_flags' => []],
                'array_count_values' => ['class' => 'Arr', 'static_method' => 'countValues', 'chain_method' => 'countValues', 'risk_flags' => []],
                'in_array' => ['class' => 'Arr', 'static_method' => 'inArray', 'chain_method' => 'inArray', 'risk_flags' => ['bool-terminal']],
                'array_is_list' => ['class' => 'Arr', 'static_method' => 'isList', 'chain_method' => 'isList', 'risk_flags' => ['bool-terminal']],
                'str_replace' => ['class' => 'Str', 'static_method' => 'replace', 'chain_method' => 'replace', 'risk_flags' => []],
                'strtolower' => ['class' => 'Str', 'static_method' => 'lower', 'chain_method' => 'lower', 'risk_flags' => []],
                'strtoupper' => ['class' => 'Str', 'static_method' => 'upper', 'chain_method' => 'upper', 'risk_flags' => []],
                'trim' => ['class' => 'Str', 'static_method' => 'trim', 'chain_method' => 'trim', 'risk_flags' => []],
                'str_contains' => ['class' => 'Str', 'static_method' => 'contains', 'chain_method' => 'contains', 'risk_flags' => ['bool-terminal']],
                'explode' => ['class' => 'Str', 'static_method' => 'split', 'chain_method' => 'split', 'risk_flags' => ['type-handoff']],
                'json_encode' => ['class' => 'Json', 'static_method' => 'encode', 'chain_method' => 'jsonEncode', 'risk_flags' => ['mixed-return', 'type-handoff']],
                'json_decode' => ['class' => 'Json', 'static_method' => 'decode', 'chain_method' => 'jsonDecode', 'risk_flags' => ['mixed-return', 'type-handoff']],
                'json_validate' => ['class' => 'Json', 'static_method' => 'validate', 'chain_method' => null, 'risk_flags' => ['static-only']],
                'json_last_error' => ['class' => 'Json', 'static_method' => 'lastError', 'chain_method' => null, 'risk_flags' => ['ambient-state', 'static-only']],
                'json_last_error_msg' => ['class' => 'Json', 'static_method' => 'lastErrorMessage', 'chain_method' => null, 'risk_flags' => ['ambient-state', 'static-only']],
                'getenv' => ['class' => 'Sys', 'static_method' => 'env', 'chain_method' => null, 'risk_flags' => ['env-read', 'static-only']],
                'gethostname' => ['class' => 'Sys', 'static_method' => 'hostname', 'chain_method' => null, 'risk_flags' => ['sys-read', 'static-only']],
                'phpversion' => ['class' => 'Sys', 'static_method' => 'version', 'chain_method' => null, 'risk_flags' => ['sys-read', 'static-only']],
                'php_sapi_name' => ['class' => 'Sys', 'static_method' => 'sapi', 'chain_method' => null, 'risk_flags' => ['sys-read', 'static-only']],
                'php_uname' => ['class' => 'Sys', 'static_method' => 'uname', 'chain_method' => null, 'risk_flags' => ['sys-read', 'static-only']],
            ],
            array_map(
                static fn (array $definition): array => [
                    'class' => $definition['class'],
                    'static_method' => $definition['static_method'],
                    'chain_method' => $definition['chain_method'],
                    'risk_flags' => $definition['risk_flags'],
                ],
                PhpFunctionMap::definitions(),
            ),
        );
    }

    public function testDerivedContractFlagsStayConsistent(): void
    {
        $definitions = PhpFunctionMap::definitions();

        self::assertTrue($definitions['array_search']['mixed_return']);
        self::assertContains('bool-terminal', $definitions['in_array']['risk_flags']);
        self::assertTrue($definitions['str_contains']['chainable']);
        self::assertFalse($definitions['json_validate']['chainable']);
        self::assertContains('ambient-state', $definitions['json_last_error']['risk_flags']);
        self::assertTrue($definitions['getenv']['side_effect']);
        self::assertNull($definitions['getenv']['chain_method']);
    }
}
