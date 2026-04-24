<?php

declare(strict_types=1);

namespace Oophp\Tests;

use PHPUnit\Framework\TestCase;

final class ApiCatalogTest extends TestCase
{
    public function testApiCatalogMatchesCurrentStaticDomainMethods(): void
    {
        $catalogPath = dirname(__DIR__) . '/catalog/api-catalog.json';
        $raw = file_get_contents($catalogPath);

        self::assertNotFalse($raw);

        $catalog = json_decode($raw, true);
        self::assertIsArray($catalog);
        self::assertArrayHasKey('domains', $catalog);
        self::assertIsArray($catalog['domains']);
        self::assertNotEmpty($catalog['domains']);

        foreach ($catalog['domains'] as $domain) {
            self::assertIsArray($domain);
            self::assertArrayHasKey('name', $domain);
            self::assertArrayHasKey('mode', $domain);
            self::assertArrayHasKey('methods', $domain);

            $fqcn = 'Oophp\\' . $domain['name'];
            self::assertTrue(class_exists($fqcn), "Catalog class {$fqcn} does not exist.");
            self::assertContains($domain['mode'], ['static-only', 'static+fluent', 'static-first']);
            self::assertIsArray($domain['methods']);

            $reflection = new \ReflectionClass($fqcn);
            $actualMethods = [];

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC) as $method) {
                if ($method->getDeclaringClass()->getName() === $fqcn) {
                    $actualMethods[] = $method->getName();
                }
            }

            sort($actualMethods);

            $expectedMethods = $domain['methods'];
            sort($expectedMethods);

            self::assertSame(
                $expectedMethods,
                $actualMethods,
                "Catalog methods for {$fqcn} are out of sync with source code.",
            );
        }
    }
}
