# Optional Extension Packs

This document defines how optional extension-dependent domains are introduced in OOPHP without bloating the core package.

## Goal

- Keep the core package focused on baseline PHP 8.3 functionality.
- Move extension-heavy APIs to explicit packs with clear install and test boundaries.
- Preserve native semantics for each wrapper, including edge cases and extension-specific behavior.

## Pack Strategy

- Each optional extension area is introduced as a separate pack (or explicit namespace layer), not mixed into unrelated core domains.
- The core package may suggest extensions in `composer.json`, but does not require them.
- Every pack must define:
  - scope (which native functions are included),
  - gate policy (what remains excluded and why),
  - test requirements (extension-present and extension-missing behavior),
  - docs entry points and usage examples.

## Initial Planned Packs

- `Intl` pack (`ext-intl`)
- `Bcmath` pack (`ext-bcmath`)
- `Ctype` pack (`ext-ctype`)
- `Iconv` pack (`ext-iconv`)

Additional packs (for example `Sockets`, `Gd`, `SimpleXml`, `Dom`, `Ffi`) can be added later using the same policy.

## Acceptance Checklist for a New Pack

- API surface is thin, native-first, and explicitly documented.
- Conformance tests cover function return contracts and edge cases.
- Missing extension behavior is handled predictably in tests/docs.
- Pack boundaries avoid leaking extension-specific APIs into core domains.
