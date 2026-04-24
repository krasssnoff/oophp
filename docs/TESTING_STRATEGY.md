# Testing Strategy (Native Conformance)

OOPHP uses native-conformance testing as the primary correctness signal.

## Goals

- Static API returns exactly what native PHP functions return.
- Fluent API preserves native behavior where the native return value is the chain value. Receiver-oriented mutators document and test their chain-specific continuation value, such as sorted arrays for sort-style methods or extracted elements for `ArrayChain::pop()` and `ArrayChain::shift()`.
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
- One explicit fluent continuation case when the chain value intentionally differs from the native function return.

## Edge-case policy

- Include mixed return cases (`false|int`, `string|false`) where applicable.
- Include strict/non-strict behavior for functions that support it.
- Include empty string and empty array behavior.
- Include key-preservation behavior where applicable.
