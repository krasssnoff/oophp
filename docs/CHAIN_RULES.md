# Chain Rules (v1)

This document defines the fluent-chain contract used by OOPHP.

## Entry and exit

- `::of($value)` creates a chain object around the initial value.
- Methods inside the chain return a new chain object.
- Use `->get()` or `()` to extract the raw PHP value at the end.

## Immutability

- Chain objects are immutable.
- Each operation creates a new wrapper with the function return value.
- No operation mutates previous chain instances.

## Native semantics first

- Every wrapped operation returns exactly what the native PHP function returns.
- The chain does not normalize return values (`false|int`, `string|false`, etc.).
- Error behavior stays native (warnings/exceptions are not remapped).

## Type handoff

- The current raw value is passed into the next operation.
- Type can legitimately change across calls (`string -> array -> int|false`, etc.).
- Users are responsible for valid call order and input types.

## Terminal behavior

- `get()` is the canonical terminal method.
- `__invoke()` is an equivalent shortcut for terminal extraction.
- Both terminal options return identical raw values.
