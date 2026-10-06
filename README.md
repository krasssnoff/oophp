# OOPHP

`OOPHP` is a Composer package that wraps selected native PHP functions with:

- static wrappers (`Arr::values(...)`, `Json::encode(...)`, etc.)
- fluent chains where receiver-based composition is natural (`Arr::of($arr)->values()->sort()->get()`)

**Requirements:** PHP `>= 8.3` (see `composer.json`).

## Project status

This package is in active development and is not published to Packagist yet.
The project is currently in alpha stage: the API is unstable and can change (including breaking changes) between versions.

## Installation (until Packagist release)

Add the GitHub repository and require the development branch:

```bash
composer config repositories.oophp vcs https://github.com/krasssnoff/oophp
composer require krasssnoff/oophp:dev-main
```

This is the same as adding the following to your project's `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/krasssnoff/oophp"
    }
  ],
  "require": {
    "krasssnoff/oophp": "dev-main"
  }
}
```

## Why OOPHP

OOPHP is OOP-first: the goal is to write PHP the way you write C#, without calling global functions at all.
Every wrapped native function is reachable as a static method of a domain class (`Math::round(...)`, much like `Math.Round(...)` in C#),
and operations on a value compose as fluent chains.

Wrappers keep native semantics 1:1: same arguments, same return values, same errors.
Unlike Laravel Collections or `symfony/string`, OOPHP adds no behavior of its own, so knowledge of the PHP manual applies unchanged.

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
    ->explode(',')
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
use Oophp\Proc;
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

$hasId = Arr::of([['id' => 1], ['id' => 2]])
    ->pop()
    ->keyExists('id')
    ->get();

$sorted = Arr::of([3, 1, 2])
    ->sort(SORT_NUMERIC)
    ->get();

$parts = Str::of('  Foo,Bar  ')
    ->trim()
    ->tolower()
    ->explode(',')
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
$distance = Math::of(-2.55)
    ->abs()
    ->round(1)
    ->pow(2)
    ->sqrt()
    ->get();

$query = Url::query(['q' => 'hello world'], '', '&', PHP_QUERY_RFC3986)->get();
$host = Url::of('https://example.com/path?q=1#frag')
    ->parse(PHP_URL_HOST)
    ->get();

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

$localhostIp = Net::gethostbyname('localhost');

$execOutput = Proc::shellExec(PHP_BINARY . ' -r "echo 42;"');

$memoryLimit = Sys::iniGet('memory_limit');
```

## Naming

- `snake_case` becomes `camelCase`: `array_key_exists` → `Arr::keyExists`, `hash_hmac` → `Hash::hashHmac`, `preg_match` → `Regex::pregMatch`.
- One-word PHP functions keep their name as is: `sort` → `Arr::sort`, `intdiv` → `Math::intdiv`, `basename` → `Fs::basename`, `gettype` → `Type::gettype`.
- A prefix that repeats the domain name is dropped so it does not appear twice: `array_*` in `Arr`, `str_*` / `str*` in `Str` (`str_contains` → `Str::contains`, `strtolower` → `Str::tolower`), and likewise `mb_*` in `MbStr`, `json_*` in `Json`, `url` in `Url` (`rawurlencode` → `Url::rawencode`).
- Helpers without a single native counterpart (`Date::startOfDay`, `Url::query`, `MbStr::contains`, …) and the workflow chains `DateChain`, `FsPathChain`, `StreamHandleChain` use descriptive names.

## Chains

- `Domain::of(...)` starts a chain; `->get()` or `()` returns the raw PHP value.
- Chains are immutable: every step returns a new chain.
- The chain type follows the value: an array continues as `ArrayChain`, a string as `StringChain` (string chains such as `MbStringChain` and `UrlChain` keep their own type), a number inside `Math` as `NumberChain`, anything else as `MixedChain`. `ValueChain::of(mixed ...)` picks the chain by the value.
- Native by-reference functions (`sort`, `shuffle`, `array_push`, `array_walk`, …) work on a copy, and the chain continues with the modified array instead of `true`. `pop()` and `shift()` continue with the removed element.
- Errors are passed through unchanged: a native `false` / `null` is returned as is (in a chain it becomes `MixedChain`), and exceptions thrown by PHP propagate.
- `ArrayChain`, `StringChain`, `MbStringChain`, `UrlChain` and `NumberChain` can hand off through JSON with `jsonEncode()` / `jsonDecode()`.

## Domains

| Domain | Wraps | Fluent entrypoint |
| --- | --- | --- |
| `Arr` | `array_*`, `in_array`, sort functions, `implode` | `Arr::of()` → `ArrayChain` |
| `Str` | selected string functions | `Str::of()` → `StringChain` (also `pregReplace`, `pregSplit`) |
| `MbStr` | selected `mb_*` functions (requires `ext-mbstring`) | `MbStr::of()` → `MbStringChain` |
| `Math` | numeric functions | `Math::of()` → `NumberChain` |
| `Url` | URL and query helpers | `Url::of()` → `UrlChain` |
| `Date` | date/time functions and immutable `DateTimeImmutable` helpers | `Date::of()` → `DateChain` |
| `Fs` | filesystem, file IO and path helpers | `Fs::of()` → `FsPathChain` |
| `Stream` | stream/resource helpers | `Stream::of()` → `StreamHandleChain` |
| `Json` | `json_*` | static only |
| `Regex` | `preg_*` | static only |
| `Enc` | base64, hex, pack/unpack, serialization | static only |
| `Hash` | hash, random and password helpers | static only |
| `Type` | `is_*`, `gettype`, `get_debug_type` | static only |
| `Net` | `gethostbyname`, `gethostbyaddr`, `dns_get_record` | static only |
| `Proc` | process execution (effectful) | static only |
| `Sys` | `ini_get`, `ini_get_all`, `extension_loaded` | static only |

## Development

- `composer test`: PHPUnit suite, including conformance tests against native PHP behavior.
- `composer analyse`: PHPStan (level 5, `src/`).
- `composer ci`: `composer validate --strict`, analysis and tests. GitHub Actions runs it on PHP 8.3, 8.4 and 8.5.

## Native function footprint (runtime inventory)

How many of PHP’s *internal* (native) functions appear as direct calls anywhere under `src/`, as a share of *all* internal functions in the current PHP build (the exact total depends on version and enabled extensions). Recompute: `php scripts/native-function-footprint.php`.

`[==                  ] 9.2%` — 192 of 2086 internal functions (PHP 8.3 in this repo’s dev environment).

## API reference

The current API surface should be read from the source files and tests.

- Source files define the actual wrappers and chain methods.
- Tests define the supported behavior and native PHP conformance.
- A final consolidated method list can be added later, once the package surface is stable.

## License

MIT, see [LICENSE](LICENSE).
