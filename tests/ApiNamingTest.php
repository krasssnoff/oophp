<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Tests\Support\ApiMap;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * Guards the naming rule from docs.md: a method name is the native name without the domain prefix,
 * in camelCase. Lowercased, it must equal the native name without prefix and underscores, so no wrapper
 * is renamed. Only methods listed in ApiMap::HELPERS are exempt.
 */
final class ApiNamingTest extends TestCase
{
    #[DataProvider('wrapperProvider')]
    public function testWrapperNameFollowsNativeName(string $class, ?string $domain, string $method, array $natives): void
    {
        self::assertCount(
            1,
            $natives,
            "{$class}::{$method} must wrap exactly one native function (found: " . implode(', ', $natives) . '). '
            . 'A method with behavior of its own must be declared in ApiMap::HELPERS.',
        );

        $expected = ApiMap::baseName($natives[0], $domain);

        self::assertSame(
            strtolower($expected),
            strtolower($method),
            "{$class}::{$method} renames {$natives[0]}(); expected {$expected} (word boundaries may differ only in case).",
        );
        self::assertSame(1, preg_match('/^[a-z]/', $method), "{$class}::{$method} must start with a lowercase letter.");

        foreach (str_split($expected) as $i => $char) {
            self::assertFalse(
                preg_match('/[A-Z]/', $char) === 1 && $method[$i] !== $char,
                "{$class}::{$method} must keep the underscore boundaries of {$natives[0]}() in camelCase: {$expected}.",
            );
        }
    }

    /**
     * @return array<string, array{string, ?string, string, list<string>}>
     */
    public static function wrapperProvider(): array
    {
        $cases = [];

        foreach (ApiMap::methods() as $entry) {
            if (!$entry['helper']) {
                $cases["{$entry['class']}::{$entry['method']}"] = [$entry['class'], $entry['domain'], $entry['method'], $entry['natives']];
            }
        }

        return $cases;
    }

    public function testChainMethodsUseTheSameNameAsStaticWrappers(): void
    {
        $methods = ApiMap::methods();
        $staticNames = [];

        foreach ($methods as $entry) {
            if ($entry['domain'] === $entry['class'] && !$entry['helper'] && \count($entry['natives']) === 1) {
                $staticNames[$entry['domain']][$entry['natives'][0]] = $entry['method'];
            }
        }

        $mismatches = [];

        foreach ($methods as $entry) {
            if ($entry['domain'] === null || $entry['domain'] === $entry['class'] || $entry['helper'] || \count($entry['natives']) !== 1) {
                continue;
            }

            $staticName = $staticNames[$entry['domain']][$entry['natives'][0]] ?? null;
            if ($staticName !== null && $staticName !== $entry['method']) {
                $mismatches[] = "{$entry['class']}::{$entry['method']} vs {$entry['domain']}::{$staticName}";
            }
        }

        self::assertSame([], $mismatches);
    }

    public function testStrKeepsPrefixWhereDroppingItBreaksTheName(): void
    {
        self::assertSame('stripTags', ApiMap::baseName('strip_tags', 'Str'));
        self::assertSame('stripslashes', ApiMap::baseName('stripslashes', 'Str'));
        self::assertSame('strchr', ApiMap::baseName('strchr', 'Str'));
        self::assertSame('ipos', ApiMap::baseName('stripos', 'Str'));
        self::assertSame('replace', ApiMap::baseName('str_replace', 'Str'));
    }

    public function testDeclaredHelpersExist(): void
    {
        $known = [];

        foreach (ApiMap::classes() as $class) {
            foreach ((new \ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $known[$method->getDeclaringClass()->getShortName() . '::' . $method->getName()] = true;
            }
        }

        $missing = [];

        foreach (ApiMap::HELPERS as $class => $helpers) {
            foreach ($helpers as $helper) {
                if (!isset($known["{$class}::{$helper}"])) {
                    $missing[] = "{$class}::{$helper}";
                }
            }
        }

        self::assertSame([], $missing);
    }
}
