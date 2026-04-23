# OOPHP

`OOPHP` is a Composer package that wraps selected native PHP functions with static entry points and a fluent chain API.

## Install

```bash
composer require krasssnoff/oophp
```

## Current scope

- `Arr` for selected `array_*` functions
- `Str` for selected string functions
- `Json` for selected `json_*` functions
- `Sys` for a few read-only system helpers
- `ValueChain` as the minimal shared fluent wrapper behind `Arr::of(...)` and `Str::of(...)`
- Typed wrappers: `ArrayChain`, `StringChain`, and supporting chain classes carry the domain methods and hand off between types as native return values change

## API principles

See `docs/CHAIN_RULES.md` for the API contract, naming rules, and fluent-chain behavior.

## Why OOPHP

Native PHP composition can become hard to scan:

```php
$position = array_search(
    'beta',
    array_values(
        explode(',', strtolower(trim('  alpha,beta,gamma  '))),
    ),
    false,
);
```

With OOPHP, the same flow stays linear:

```php
$position = Str::of('  alpha,beta,gamma  ')
    ->trim()
    ->tolower()
    ->split(',')
    ->values()
    ->search('beta')();
```

## Examples

```php
use Oophp\Arr;
use Oophp\Json;
use Oophp\Str;

$position = Arr::of(['a' => 'first', 'b' => 'second'])
    ->values()
    ->search('second')();

$parts = Str::of('  Foo,Bar  ')
    ->trim()
    ->tolower()
    ->split(',')
    ->get();

$values = Arr::values(['x' => 10, 'y' => 20]);
$contains = Str::contains('package', 'ack');
$json = Json::encode(['ok' => true]);
```

`->get()` remains available when you prefer an explicit terminal call.

`Json` remains a static-only domain because JSON operations do not map naturally to a receiver-based chain model.

## Design and test docs

- `docs/CHAIN_RULES.md` - fluent-chain contract
- `docs/TESTING_STRATEGY.md` - native conformance and TDD workflow

## API reference

The current API surface should be read from the source files and tests.

- Source files define the actual wrappers and chain methods.
- Tests define the supported behavior and native PHP conformance.
- A final consolidated method list can be added later, once the package surface is stable.
