# Project Principles

This document defines the core API principles used by OOPHP.

## Core model

- `Arr`, `Str`, and extension-gated `MbStr` provide static wrappers and fluent chains via `::of(...)`.
- `Math`, `Json`, `Url`, and `Sys` are static-only.
- Fluent chains wrap the current value and pass it into the next native operation.

## Native-first contract

- Wrapped methods preserve native PHP semantics as closely as possible.
- The package does not normalize return values such as `false|int`, `string|false`, or `array|false`.
- Error behavior stays native.

## Chain behavior

- All fluent methods always return a chain object.
- Raw PHP values are extracted only through `->get()` or `()`.
- There are no special terminal fluent methods that directly return raw values.
- `ValueChain` keeps only shared wrapper mechanics; domain methods live on `ArrayChain`, `StringChain`, `MbStringChain`, and supporting chain classes.
- Fluent calls hand off to the next typed chain based on the native return value: arrays become `ArrayChain`, strings become `StringChain`, and all other values stay in `MixedChain`.

## Immutability

- Chain objects are immutable.
- Each operation creates a new wrapper with the function return value.
- Previous chain instances are never mutated.

## Naming

- Each mapping follows `native PHP function -> static wrapper -> chain method`.
- Method names are derived conservatively from native PHP names.
- CamelCase conversion is applied only to underscore-separated names.
- The `str` prefix may be removed only when it is a leading native prefix, and it must not be removed from the middle of a name.
- No additional shortening is allowed beyond that rule.
- Examples: `strtolower -> tolower`, `strtoupper -> toupper`, `substr_replace -> substrReplace`, while `trim -> trim`.
- Static wrapper names stay short and domain-oriented.
- Chain methods reuse the static wrapper name where possible.
