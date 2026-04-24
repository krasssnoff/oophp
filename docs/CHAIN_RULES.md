# Project Principles

This document defines the core API principles used by OOPHP.

## Core model

- `Arr`, `Str`, extension-gated `MbStr`, `Date`, `Fs`, `Stream`, `Math`, and `Url` provide static wrappers and fluent chains via `::of(...)`.
- `Json`, `Enc`, `Hash`, `Type`, `Net`, `Proc`, and `Sys` are static-only.
- `Regex` is static-first, while receiver-friendly regex transforms are exposed on `StringChain`.
- `Sys` is read-only by policy and does not include process/mutation APIs.
- `Proc` is explicitly effectful and remains static-only.
- Fluent chains wrap the current value and pass it into the next native operation.

## Native-first contract

- Wrapped methods preserve native PHP semantics as closely as possible.
- The package does not normalize return values such as `false|int`, `string|false`, or `array|false`.
- Error behavior stays native.

## Chain behavior

- All fluent methods always return a chain object.
- Raw PHP values are extracted only through `->get()` or `()`.
- There are no special terminal fluent methods that directly return raw values.
- `MixedChain` can act as an interoperability bridge via `jsonEncode(...)` / `jsonDecode(...)`.
- `ValueChain` keeps only shared wrapper mechanics; domain methods live on `ArrayChain`, `StringChain`, `MbStringChain`, `DateChain`, `FsPathChain`, `StreamHandleChain`, `NumberChain`, `UrlChain`, and supporting chain classes.
- Fluent calls hand off to the next typed chain based on the native return value: arrays become `ArrayChain`, strings become `StringChain`, and all other values stay in `MixedChain`.

## Immutability

- Chain objects are immutable.
- Each operation creates a new wrapper with the function return value.
- Previous chain instances are never mutated.

## Naming

- Each mapping follows `native PHP function -> static wrapper -> chain method`.
- Method names are derived conservatively from native PHP names.
- Wrapper names should stay close to the native function, but may be normalized for readability when the native name is dense or hard to scan.
- CamelCase conversion is applied to underscore-separated names and may also be used to split dense native names into readable words when the mapping stays obvious.
- The `str` prefix may be removed only when it is a leading native prefix, and it must not be removed from the middle of a name.
- Avoid free-form renames: the wrapper should still be easy to trace back to the original PHP function without a lookup table.
- Prefer readable normalizations over clever aliases; examples: `strtolower -> tolower`, `strtoupper -> toupper`, `substr_replace -> substrReplace`, `gethostbyname -> getHostByName`, `ip2long -> ipToLong`, while `trim -> trim`.
- Static wrapper names stay short and domain-oriented.
- Chain methods reuse the static wrapper name where possible.
