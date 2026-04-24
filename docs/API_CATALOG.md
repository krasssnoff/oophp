# API Catalog and Coverage Tracking

OOPHP tracks the current wrapper surface in a machine-readable catalog:

- `catalog/api-catalog.json`

## What it contains

- domain name (`Arr`, `Str`, `Json`, etc.),
- domain mode (`static-only`, `static+fluent`, `static-first`),
- declared static method list for each domain class.

## Why this exists

- Provides a scalable inventory of the current API surface.
- Makes review of coverage progress explicit during roadmap expansion.
- Prevents silent drift between docs and implementation.

## Sync policy

- When adding or removing domain methods, update `catalog/api-catalog.json` in the same change.
- `tests/ApiCatalogTest.php` verifies that the catalog matches source methods via reflection.
- If the test fails, the catalog is stale and must be updated.
