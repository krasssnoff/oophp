# OOPHP

`OOPHP` is a Composer package that wraps selected native PHP functions with:

- static wrappers (`Arr::values(...)`, `Json::encode(...)`, etc.)
- fluent chains where receiver-based composition is natural (`Arr::of(...)`, `Str::of(...)`)

## Project status

This package is in active development and is not published to Packagist yet.

## Local usage (until Packagist release)

```bash
git clone https://github.com/krasssnoff/oophp.git
cd oophp
composer install
```

## Current scope

- `Arr` for selected `array_*` functions
- `Str` for selected string functions
- `MbStr` for selected `mb_*` functions (optional `ext-mbstring`)
- `Math` for selected numeric wrappers
- `Json` for selected `json_*` functions
- `Url` for selected URL/query helpers
- `Encoding` for base64/hex/pack/unpack and serialization helpers
- `Preg` for selected `preg_*` regex operations
- `Path` for selected path helpers
- `Fs` for selected filesystem and file IO helpers
- `Stream` for selected stream/resource helpers
- `Time` for selected procedural date/time helpers
- `Hash` for selected hash/random/password helpers
- `Type` for selected value/type inspection and cast helpers
- `Sys` for read-only system/runtime helpers
- Fluent API is available for `Arr` and `Str`
- Fluent API is also available for `MbStr` when `ext-mbstring` is installed
- Receiver-friendly regex transforms are available on `StringChain` (`pregReplace`, `pregSplit`)
- `Math`, `Json`, `Url`, `Encoding`, `Path`, `Fs`, `Stream`, `Time`, `Hash`, `Type`, and `Sys` are static-only domains
- `ValueChain` is the minimal shared chain wrapper
- Typed chains (`ArrayChain`, `StringChain`, `MixedChain`) carry domain methods and handle type handoff

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
use Oophp\Encoding;
use Oophp\Json;
use Oophp\Math;
use Oophp\MbStr;
use Oophp\Fs;
use Oophp\Hash;
use Oophp\Path;
use Oophp\Preg;
use Oophp\Stream;
use Oophp\Sys;
use Oophp\Time;
use Oophp\Type;
use Oophp\Str;
use Oophp\Url;

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

$chars = MbStr::of('ПрИвЕт')
    ->tolower('UTF-8')
    ->split(2, 'UTF-8')
    ->get();

$json = Json::encode(['ok' => true]);
$decoded = Json::decode($json, true);

$rounded = Math::round(2.55, 1);

$query = Url::buildQuery(['q' => 'hello world'], '', '&', PHP_QUERY_RFC3986);

$encoded = Encoding::base64Encode('hello');

$matched = Preg::pregMatch('/\w+/', 'alpha');

$filename = Path::basename('/var/www/app/archive.tar.gz');

$written = Fs::filePutContents('/tmp/example.txt', 'payload');

$handle = Stream::fopen('/tmp/example.txt', 'r');

$tomorrow = Time::strtotime('+1 day');

$digest = Hash::hash('sha256', 'payload');

$isNumeric = Type::isNumeric('42');

$memoryLimit = Sys::iniGet('memory_limit');

$sapi = Sys::sapi();
```

Use `->get()` or `()` to extract raw PHP values from a chain.

`Math`, `Json`, `Url`, `Encoding`, `Path`, `Fs`, `Stream`, `Time`, `Hash`, `Type`, and `Sys` remain static-only domains.

## Design and test docs

- `docs/CHAIN_RULES.md` - fluent-chain contract
- `docs/TESTING_STRATEGY.md` - native conformance and TDD workflow

## API reference

The current API surface should be read from the source files and tests.

- Source files define the actual wrappers and chain methods.
- Tests define the supported behavior and native PHP conformance.
- A final consolidated method list can be added later, once the package surface is stable.
