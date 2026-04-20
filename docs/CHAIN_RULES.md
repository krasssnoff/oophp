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

## Naming contract

- `PHP function -> static wrapper -> chain method` is a frozen v1 mapping.
- Static wrapper names stay short and domain-oriented (`array_values -> Arr::values`, `strtolower -> Str::lower`).
- Chain methods reuse the static wrapper name when the current value can naturally become the first native argument.
- Chain methods may use an explicit domain prefix only to avoid ambiguous names in mixed chains (`Json::encode -> ->jsonEncode()`, `Json::decode -> ->jsonDecode()`).
- Factory methods such as `Arr::of()`, `Str::of()`, and `Json::of()` are chain entry points, not part of the PHP-function mapping table.

## Chain eligibility

- A chain method exists only when the current value can act as the receiver without hiding native argument order.
- Functions that depend on ambient state or do not consume the current value stay static-only (`json_validate`, `json_last_error`, `json_last_error_msg`).
- The `Sys` domain is static-only in v1. Its wrappers are intentionally excluded from fluent chains.

## Type handoff

- The current raw value is passed into the next operation.
- Type can legitimately change across calls (`string -> array -> int|false`, etc.).
- Users are responsible for valid call order and input types.
- Some methods are explicit handoff boundaries and should be treated as such in API reviews (`split`, `jsonEncode`, `jsonDecode`).

## Terminal behavior

- `get()` is the canonical terminal method.
- `__invoke()` is an equivalent shortcut for terminal extraction.
- Both terminal options return identical raw values.
