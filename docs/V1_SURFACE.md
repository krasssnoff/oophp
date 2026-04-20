# v1 Surface and Risk Groups

This file defines which function groups are intentionally in v1.

## Naming policy

- Each entry maps `native PHP function -> static wrapper -> chain method`.
- `null` chain methods are intentionally static-only, not missing implementations.
- `jsonEncode` and `jsonDecode` keep the `json` prefix in fluent chains to stay unambiguous inside mixed chains.

## Risk flags

- `mixed-return`: native return type is not a single stable scalar/object shape.
- `type-handoff`: the method commonly changes the chain value type for the next step.
- `bool-terminal`: the method usually ends a fluent branch with a boolean result.
- `static-only`: there is no receiver-safe fluent form in v1.
- `ambient-state`: result depends on previously executed JSON operations.
- `env-read`: reads environment-dependent process state.
- `sys-read`: reads host/runtime metadata.

## v1 whitelist (implemented)

### Arr

- `array_values` -> `Arr::values`, chain `->values()`, flags: none
- `array_keys` -> `Arr::keys`, chain `->keys()`, flags: none
- `array_search` -> `Arr::search`, chain `->search(...)`, flags: `mixed-return`
- `array_filter` -> `Arr::filter`, chain `->filter(...)`, flags: none
- `array_map` -> `Arr::map`, chain `->map(...)`, flags: none
- `array_reverse` -> `Arr::reverse`, chain `->reverse(...)`, flags: none

### Str

- `str_replace` -> `Str::replace`, chain `->replace(...)`, flags: none
- `strtolower` -> `Str::lower`, chain `->lower()`, flags: none
- `strtoupper` -> `Str::upper`, chain `->upper()`, flags: none
- `trim` -> `Str::trim`, chain `->trim(...)`, flags: none
- `str_contains` -> `Str::contains`, chain `->contains(...)`, flags: `bool-terminal`
- `explode` -> `Str::split`, chain `->split(...)`, flags: `type-handoff`

### Json

- `json_encode` -> `Json::encode`, chain `->jsonEncode(...)`, flags: `mixed-return`, `type-handoff`
- `json_decode` -> `Json::decode`, chain `->jsonDecode(...)`, flags: `mixed-return`, `type-handoff`
- `json_validate` -> `Json::validate`, chain `null`, flags: `static-only`
- `json_last_error` -> `Json::lastError`, chain `null`, flags: `ambient-state`, `static-only`
- `json_last_error_msg` -> `Json::lastErrorMessage`, chain `null`, flags: `ambient-state`, `static-only`

### Sys (static-only, read-first)

- `getenv` -> `Sys::env`, chain `null`, flags: `env-read`, `static-only`
- `gethostname` -> `Sys::hostname`, chain `null`, flags: `sys-read`, `static-only`
- `phpversion` -> `Sys::version`, chain `null`, flags: `sys-read`, `static-only`
- `php_sapi_name` -> `Sys::sapi`, chain `null`, flags: `sys-read`, `static-only`
- `php_uname` -> `Sys::uname`, chain `null`, flags: `sys-read`, `static-only`

## risky groups (post-v1 or guarded)

- **By-reference mutation:** `sort`, `rsort`, `shuffle`, `array_multisort`.
- **Output-parameter style:** `preg_match`, `parse_str`.
- **Side-effect heavy:** `exec`, `system`, file write operations, process control.
- **Extension-bound groups:** `mb_*`, `intl`, `gd`, `pcntl`, `posix`.

These groups require explicit policy before inclusion in fluent chains.
