# OOPHP

`OOPHP` is a Composer package that wraps selected native PHP functions with static entry points and a fluent chain API.

## Current v1 scope

- `Arr` for selected `array_*` functions
- `Str` for selected string functions
- `Json` for selected `json_*` functions
- `Sys` for a few read-only system helpers
- `ValueChain` as the fluent wrapper behind `Arr::of(...)`, `Str::of(...)`, and `Json::of(...)`

## Design rules

- Static methods stay close to native PHP signatures.
- Fluent calls carry the previous raw return value into the next method.
- The package does not normalize native PHP behavior.
- Use `->get()` or `()` when you want to extract the raw PHP value from the chain.

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
    ->lower()
    ->split(',')
    ->get();

$values = Arr::values(['x' => 10, 'y' => 20]);
$contains = Str::contains('package', 'ack');
$json = Json::encode(['ok' => true]);
```

`->get()` remains available when you prefer an explicit terminal call.

## Implemented methods

### `Arr`

- `of`
- `values`
- `keys`
- `search`
- `filter`
- `map`
- `reverse`

### `Str`

- `of`
- `replace`
- `lower`
- `upper`
- `trim`
- `contains`
- `split`

### `Json`

- `of`
- `encode`
- `decode`
- `validate`
- `lastError`
- `lastErrorMessage`

### `Sys`

- `env`
- `hostname`
- `version`
- `sapi`
- `uname`
