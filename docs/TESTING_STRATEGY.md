# Testing Strategy (Native Conformance)

OOPHP uses native-conformance testing as the primary correctness signal.

## Goals

- Static API returns exactly what native PHP functions return.
- Fluent API preserves the same native behavior, and after terminal extraction via `->get()` or `()` returns the same raw values as equivalent native calls.
- Type transitions in chains are explicit and preserved.

## TDD workflow

1. Add or update tests that compare OOPHP output to native PHP output.
2. Run tests and observe failure for new behavior.
3. Implement the minimum code needed to pass tests.
4. Add edge-case tests before moving to the next function group.

## Required coverage per wrapped function

- One static API conformance case.
- One fluent API conformance case (when chain method exists).
- At least one edge case relevant to return type or function mode.

## Edge-case policy

- Include mixed return cases (`false|int`, `string|false`) where applicable.
- Include strict/non-strict behavior for functions that support it.
- Include empty string and empty array behavior.
- Include key-preservation behavior where applicable.
