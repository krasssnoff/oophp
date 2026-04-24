# Docs Navigation

Use this index as the entry point for package conventions, roadmap-adjacent policies, and test strategy.

## Core Design Docs

- `docs/CHAIN_RULES.md` - API contract, fluent/static boundaries, and naming policy.
- `docs/TESTING_STRATEGY.md` - native-conformance and TDD workflow.
- `docs/API_CATALOG.md` - machine-readable catalog policy and sync checks.
- `docs/EXTENSION_PACKS.md` - optional extension pack strategy and acceptance checklist.
- `docs/RELEASE_PROCESS.md` - release readiness and publishing checklist.

## Domain Surface Overview

Current domains are tracked in `catalog/api-catalog.json`.

- `static+fluent`: `Arr`, `Str`, `MbStr`
- `static-first`: `Preg` (with receiver-friendly fluent transforms on `StringChain`)
- `static-only`: `Math`, `Json`, `Url`, `Encoding`, `Path`, `Fs`, `Stream`, `Time`, `Hash`, `Type`, `Network`, `Process`, `Sys`
