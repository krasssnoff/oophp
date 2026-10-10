<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Tests\Support\ApiMap;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Guards "same arguments" from docs.md: a wrapper takes the native parameters under their camelCase names,
 * passes by reference where the native does and keeps native defaults. A static wrapper keeps the native order;
 * a chain method takes the native parameters without the receiver. Helpers are exempt.
 *
 * A variadic wrapper parameter stands for native parameters that have no default (`array_keys`) and is not compared.
 * Object defaults (`RoundingMode` since PHP 8.4) are not compared: signatures follow PHP 8.3.
 */
final class ApiSignatureTest extends TestCase
{
    #[DataProvider('staticProvider')]
    public function testStaticWrapperKeepsNativeSignature(string $class, string $method, string $native): void
    {
        $wrapper = (new ReflectionMethod($class, $method))->getParameters();
        $label = substr((string) strrchr($class, '\\'), 1) . "::{$method}";
        $parameters = (new ReflectionFunction($native))->getParameters();

        foreach ($parameters as $i => $parameter) {
            $actual = $wrapper[$i] ?? null;
            if ($actual !== null && $actual->isVariadic() && !$parameter->isVariadic()) {
                return;
            }

            self::assertNotNull($actual, "{$label}() drops \${$parameter->getName()} of {$native}().");
            self::assertParameterMatches($label, $native, $actual, $parameter);
        }

        self::assertCount(\count($parameters), $wrapper, "{$label}() takes more parameters than {$native}().");
    }

    #[DataProvider('chainProvider')]
    public function testChainMethodKeepsNativeSignatureWithoutReceiver(string $class, string $method, string $native): void
    {
        $wrapper = (new ReflectionMethod($class, $method))->getParameters();
        $label = substr((string) strrchr($class, '\\'), 1) . "::{$method}";
        $parameters = [];
        foreach ((new ReflectionFunction($native))->getParameters() as $parameter) {
            $parameters[self::camelCase($parameter->getName())] = $parameter;
        }

        $variadic = false;
        foreach ($wrapper as $actual) {
            if ($actual->isVariadic() && !isset($parameters[$actual->getName()])) {
                $variadic = true;

                continue;
            }

            self::assertArrayHasKey($actual->getName(), $parameters, "{$label}() takes \${$actual->getName()}, unknown to {$native}().");
            self::assertParameterMatches($label, $native, $actual, $parameters[$actual->getName()]);
        }

        if ($variadic || end($parameters)->isVariadic()) {
            self::assertLessThanOrEqual(\count($parameters), \count($wrapper), "{$label}() takes more parameters than {$native}().");
        } else {
            self::assertCount(\count($parameters) - 1, $wrapper, "{$label}() must take every parameter of {$native}() except the receiver.");
        }
    }

    /**
     * @return array<string, array{class-string, string, string}>
     */
    public static function staticProvider(): array
    {
        return self::cases(static: true);
    }

    /**
     * @return array<string, array{class-string, string, string}>
     */
    public static function chainProvider(): array
    {
        return self::cases(static: false);
    }

    /**
     * @return array<string, array{class-string, string, string}>
     */
    private static function cases(bool $static): array
    {
        $cases = [];

        foreach (ApiMap::methods() as $entry) {
            if ($entry['helper'] || \count($entry['natives']) !== 1 || ($entry['domain'] === $entry['class']) !== $static) {
                continue;
            }

            $class = ($static ? 'Oophp\\' : 'Oophp\\Chain\\') . $entry['class'];
            $cases["{$entry['class']}::{$entry['method']}"] = [$class, $entry['method'], $entry['natives'][0]];
        }

        return $cases;
    }

    private static function assertParameterMatches(string $method, string $native, ReflectionParameter $actual, ReflectionParameter $expected): void
    {
        $name = $expected->getName();

        self::assertSame(self::camelCase($name), $actual->getName(), "{$method}() must name \${$name} of {$native}() in camelCase.");
        self::assertSame($expected->isVariadic(), $actual->isVariadic(), "{$method}(): \${$actual->getName()} variadic mismatch with {$native}().");

        if (!$expected->isVariadic()) {
            self::assertSame($expected->isPassedByReference(), $actual->isPassedByReference(), "{$method}(): \${$actual->getName()} by-reference mismatch with {$native}().");
            self::assertSame($expected->isOptional(), $actual->isOptional(), "{$method}(): \${$actual->getName()} optional mismatch with {$native}().");
        }

        if ($expected->isDefaultValueAvailable() && !\is_object($expected->getDefaultValue())) {
            self::assertTrue($actual->isDefaultValueAvailable(), "{$method}(): \${$actual->getName()} needs the default of {$native}().");
            self::assertSame($expected->getDefaultValue(), $actual->getDefaultValue(), "{$method}(): \${$actual->getName()} default differs from {$native}().");
        }
    }

    private static function camelCase(string $name): string
    {
        return lcfirst(str_replace('_', '', ucwords($name, '_')));
    }
}
