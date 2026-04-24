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
- `Enc` for base64/hex/pack/unpack and serialization helpers
- `Regex` for selected `preg_*` regex operations
- `Fs` for selected filesystem/file IO helpers, including path helpers
- `Stream` for selected stream/resource helpers with optional fluent handle workflow
- `Date` for rich immutable date/time wrappers with fluent entrypoint
- `Hash` for selected hash/random/password helpers
- `Type` for selected value/type inspection and cast helpers
- `Net` for selected DNS/network utility helpers
- `Process` for explicit effectful process/exec helpers
- `Sys` for read-only system/runtime helpers
- Fluent API is available for `Arr` and `Str`
- Fluent API is also available for `MbStr` when `ext-mbstring` is installed
- Receiver-friendly regex transforms are available on `StringChain` (`pregReplace`, `pregSplit`)
- `Date` also exposes immutable fluent chains via `Date::of(...)`
- `Fs` and `Stream` expose compact workflow chains via `Fs::of(...)` and `Stream::of(...)`
- `MixedChain` exposes JSON bridge helpers (`jsonEncode`, `jsonDecode`) for chain handoff
- `Math`, `Json`, `Url`, `Enc`, `Hash`, `Type`, `Net`, `Process`, and `Sys` are static-only domains
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
use Oophp\Enc;
use Oophp\Json;
use Oophp\Math;
use Oophp\MbStr;
use Oophp\Net;
use Oophp\Fs;
use Oophp\Hash;
use Oophp\Process;
use Oophp\Regex;
use Oophp\Stream;
use Oophp\Sys;
use Oophp\Date;
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

$encoded = Enc::base64Encode('hello');

$matched = Regex::pregMatch('/\w+/', 'alpha');

$filename = Fs::basename('/var/www/app/archive.tar.gz');

$written = Fs::filePutContents('/tmp/example.txt', 'payload');

$handle = Stream::fopen('/tmp/example.txt', 'r');

$tomorrow = Date::strtotime('+1 day');
$windowEnd = Date::of('2024-01-10 14:30:00', 'UTC')
    ->modify('+2 days')
    ->endOfDay()
    ->format('c')
    ->get();

$digest = Hash::hash('sha256', 'payload');

$isNumeric = Type::isNumeric('42');

$localhostIp = Net::getHostByName('localhost');

$execOutput = Process::shellExec(PHP_BINARY . ' -r "echo 42;"');

$memoryLimit = Sys::iniGet('memory_limit');

$sapi = Sys::sapi();
```

Use `->get()` or `()` to extract raw PHP values from a chain.

`Arr`, `Str`, `MbStr`, `Date`, `Fs`, and `Stream` are static+fluent, while `Math`, `Json`, `Url`, `Enc`, `Hash`, `Type`, `Net`, `Process`, and `Sys` remain static-only.

## Design and test docs

- `docs/DOCS_NAVIGATION.md` - documentation entry point and domain map
- `docs/CHAIN_RULES.md` - fluent-chain contract
- `docs/API_CATALOG.md` - machine-readable API catalog and sync policy
- `docs/EXTENSION_PACKS.md` - optional extension pack strategy
- `docs/TESTING_STRATEGY.md` - native conformance and TDD workflow
- `docs/RELEASE_PROCESS.md` - release checklist and publishing flow

## CI and releases

- Local CI command: `composer ci` (composer validation + full test suite).
- Release readiness command: `composer release:check`.

## API reference

The current API surface should be read from the source files and tests.

- Source files define the actual wrappers and chain methods.
- Tests define the supported behavior and native PHP conformance.
- A final consolidated method list can be added later, once the package surface is stable.
