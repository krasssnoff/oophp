# v1 Surface and Risk Groups

This file defines which function groups are intentionally in v1.

## v1 whitelist (implemented)

### Arr

- `array_values` -> `Arr::values`, chain `->values()`
- `array_keys` -> `Arr::keys`, chain `->keys()`
- `array_search` -> `Arr::search`, chain `->search(...)`
- `array_filter` -> `Arr::filter`, chain `->filter(...)`
- `array_map` -> `Arr::map`, chain `->map(...)`
- `array_reverse` -> `Arr::reverse`, chain `->reverse(...)`

### Str

- `str_replace` -> `Str::replace`, chain `->replace(...)`
- `strtolower` -> `Str::lower`, chain `->lower()`
- `strtoupper` -> `Str::upper`, chain `->upper()`
- `trim` -> `Str::trim`, chain `->trim(...)`
- `str_contains` -> `Str::contains`, chain `->contains(...)`
- `explode` -> `Str::split`, chain `->split(...)`

### Json

- `json_encode` -> `Json::encode`, chain `->jsonEncode(...)`
- `json_decode` -> `Json::decode`, chain `->jsonDecode(...)`
- `json_validate` -> `Json::validate` (static only)
- `json_last_error` -> `Json::lastError` (static only)
- `json_last_error_msg` -> `Json::lastErrorMessage` (static only)

### Sys (static-only, read-first)

- `getenv` -> `Sys::env`
- `gethostname` -> `Sys::hostname`
- `phpversion` -> `Sys::version`
- `php_sapi_name` -> `Sys::sapi`
- `php_uname` -> `Sys::uname`

## risky groups (post-v1 or guarded)

- **By-reference mutation:** `sort`, `rsort`, `shuffle`, `array_multisort`.
- **Output-parameter style:** `preg_match`, `parse_str`.
- **Side-effect heavy:** `exec`, `system`, file write operations, process control.
- **Extension-bound groups:** `mb_*`, `intl`, `gd`, `pcntl`, `posix`.

These groups require explicit policy before inclusion in fluent chains.
