<?php

declare(strict_types=1);

/**
 * Counts how many of PHP's internal (native) functions are referenced
 * in project source, vs total internal functions in the current runtime.
 *
 * Usage: php scripts/native-function-footprint.php
 */

$internalList = get_defined_functions()['internal'];
$internal = array_fill_keys($internalList, true);
$totalInternal = \count($internal);

$iter = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        __DIR__ . '/../src',
        FilesystemIterator::SKIP_DOTS
    )
);

$found = [];

foreach ($iter as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }
    $code = file_get_contents($file->getPathname());
    if ($code === false) {
        throw new \RuntimeException('Failed to read: ' . $file->getPathname());
    }
    $stripped = _stripNonCode($code);
    if ($stripped === '') {
        continue;
    }
    $calls = _extractCallLikeIdentifiers($stripped);
    foreach ($calls as $name) {
        if (isset($internal[$name])) {
            $found[$name] = true;
        }
    }
}

$used = \count($found);
$percent = $totalInternal > 0 ? round(100.0 * $used / $totalInternal, 2) : 0.0;

echo "total_internal: {$totalInternal}\n";
echo "used_in_src: {$used}\n";
echo "percent: {$percent}\n";

/**
 * @return list<string>
 */
function _stripNonCode(string $code): string
{
    $tokens = token_get_all($code);
    $out = '';
    foreach ($tokens as $t) {
        if (\is_array($t)) {
            if ($t[0] === T_WHITESPACE) {
                $out .= ' ';
                continue;
            }
            if ($t[0] === T_DOC_COMMENT || $t[0] === T_COMMENT) {
                continue;
            }
            if ($t[0] === T_CONSTANT_ENCAPSED_STRING) {
                $out .= "''";
                continue;
            }
            if ($t[0] === T_ENCAPSED_AND_WHITESPACE) {
                $out .= "''";
                continue;
            }
            if ($t[0] === T_STRING) {
                $out .= $t[1];

                continue;
            }
            $out .= ' ';

            continue;
        }
        $out .= $t;
    }

    return $out;
}

/**
 * @return list<string>
 */
function _extractCallLikeIdentifiers(string $stripped): array
{
    if (!preg_match_all(
        '/(?<![$\\\\a-zA-Z0-9_\\x7f-\\xff])(?<name>[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*)\\h*\\(/u',
        $stripped,
        $matches,
    )) {
        return [];
    }

    return $matches['name'];
}
