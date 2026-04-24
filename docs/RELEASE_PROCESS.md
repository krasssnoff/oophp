# Release Process

This checklist keeps releases repeatable while the package surface is expanding.

## Pre-release checks

1. Ensure `main` is clean and up to date.
2. Run `composer ci` locally.
3. Confirm `catalog/api-catalog.json` is synchronized (validated by `tests/ApiCatalogTest.php`).
4. Verify docs updates for any newly added domains/policies.

## Tagging and publishing

1. Create a semver tag (for example `v0.8.0`).
2. Push the tag to origin.
3. Publish release notes on GitHub from the tag (manual or automated in your CI environment).

## Packaging notes

- Keep `composer.json` suggestions aligned with extension-pack strategy.
- Prefer one roadmap stage per PR to simplify release notes and rollback.
