<?php

declare(strict_types=1);

namespace Oophp\Tests\Support;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

/**
 * Inventory of the public API: which native function every public method wraps.
 * Shared by ApiNamingTest (naming guard) and scripts/api-map.php (docs.md generator).
 */
final class ApiMap
{
    /**
     * Prefix of the native name that repeats the domain and is dropped from the method name.
     * `Str` keeps `str` in `strip*` (but not `stripos`) and `strchr`: `strip_tags` → `stripTags`, not `ipTags`.
     */
    public const DOMAIN_PREFIXES = [
        'Arr' => '/^array_/',
        'Str' => '/^str(?!ip(?!os)|chr)_?/',
        'MbStr' => '/^mb_(str_?)?/',
        'Json' => '/^json_/',
        'Url' => '/url/',
        'Regex' => '/^preg_/',
        'Hash' => '/^hash_/',
        'Stream' => '/^stream_/',
        'Proc' => '/^proc_/',
    ];

    /**
     * Chain class => domain whose names it mirrors.
     */
    public const CHAIN_DOMAINS = [
        'ArrayChain' => 'Arr',
        'StringChain' => 'Str',
        'MbStringChain' => 'MbStr',
        'NumberChain' => 'Math',
        'UrlChain' => 'Url',
        'DateChain' => 'Date',
        'FsPathChain' => 'Fs',
        'StreamHandleChain' => 'Stream',
    ];

    /**
     * Methods with behavior of their own (no single native counterpart). The only exceptions to the naming rule.
     */
    public const HELPERS = [
        'Date' => [
            'now', 'parse', 'fromTimestamp', 'create', 'createFromFormat', 'timezone',
            'format', 'timestamp', 'diff', 'startOfDay', 'endOfDay', 'range',
        ],
        'MbStr' => ['contains', 'startsWith', 'endsWith'],
        'DateChain' => [
            'timezone', 'modify', 'setDate', 'setTime', 'startOfDay', 'endOfDay',
            'add', 'sub', 'format', 'timestamp', 'diff', 'isBefore', 'isAfter',
        ],
        'FsPathChain' => ['normalize', 'copyTo', 'renameTo'],
        'MbStringChain' => ['contains', 'startsWith', 'endsWith'],
    ];

    /**
     * Chain entrypoints and terminals, not wrappers.
     */
    private const INFRASTRUCTURE = ['of', 'get', '__invoke', '__construct'];

    /**
     * @return list<array{class: string, domain: ?string, method: string, natives: list<string>, helper: bool}>
     */
    public static function methods(): array
    {
        $methods = [];

        foreach (self::classes() as $class) {
            $reflection = new ReflectionClass($class);
            $short = $reflection->getShortName();

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getDeclaringClass()->getName() !== $class || \in_array($method->getName(), self::INFRASTRUCTURE, true)) {
                    continue;
                }

                $methods[] = [
                    'class' => $short,
                    'domain' => self::domainOf($reflection),
                    'method' => $method->getName(),
                    'natives' => self::nativeCalls($method),
                    'helper' => \in_array($method->getName(), self::HELPERS[$short] ?? [], true),
                ];
            }
        }

        return $methods;
    }

    /**
     * @return list<class-string>
     */
    public static function classes(): array
    {
        $root = \dirname(__DIR__, 2) . '/src';
        $classes = [];

        foreach (['' => 'Oophp\\', '/Chain' => 'Oophp\\Chain\\'] as $dir => $namespace) {
            $files = glob($root . $dir . '/*.php') ?: [];
            sort($files);

            foreach ($files as $file) {
                $classes[] = $namespace . basename($file, '.php');
            }
        }

        return $classes;
    }

    /**
     * Domain whose prefix rule applies to the class: the class itself for a domain, the mirrored domain for a chain.
     */
    public static function domainOf(ReflectionClass $class): ?string
    {
        if ($class->getNamespaceName() === 'Oophp') {
            return $class->getShortName();
        }

        return self::CHAIN_DOMAINS[$class->getShortName()] ?? null;
    }

    /**
     * Native name with the domain prefix dropped, in camelCase at the underscore boundaries: `array_key_exists` → `keyExists`.
     */
    public static function baseName(string $native, ?string $domain): string
    {
        $pattern = $domain === null ? null : self::DOMAIN_PREFIXES[$domain] ?? null;
        $stripped = $pattern === null ? $native : (string) preg_replace($pattern, '', $native, 1);

        return lcfirst(str_replace(' ', '', ucwords(trim(str_replace('_', ' ', $stripped)))));
    }

    /**
     * @return list<string>
     */
    private static function nativeCalls(ReflectionMethod $method): array
    {
        $lines = file((string) $method->getFileName()) ?: [];
        $source = implode('', \array_slice($lines, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
        $tokens = array_values(array_filter(
            token_get_all('<?php ' . $source),
            static fn (array|string $token): bool => !\is_array($token) || !\in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true),
        ));
        $calls = [];

        foreach ($tokens as $i => $token) {
            if (!\is_array($token) || !\in_array($token[0], [T_STRING, T_NAME_FULLY_QUALIFIED], true) || ($tokens[$i + 1] ?? null) !== '(') {
                continue;
            }

            $previous = $tokens[$i - 1] ?? null;
            if (\is_array($previous) && \in_array($previous[0], [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR, T_DOUBLE_COLON, T_FUNCTION, T_NEW], true)) {
                continue;
            }

            $name = ltrim($token[1], '\\');
            if (\function_exists($name) && (new ReflectionFunction($name))->isInternal()) {
                $calls[$name] = true;
            }
        }

        return array_keys($calls);
    }
}
