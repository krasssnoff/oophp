<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TypeTest extends TestCase
{
    public function testTypeDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Type::class, 'of'));
    }

    #[DataProvider('staticProvider')]
    public function testStaticMethodsMatchNativePhp(mixed $expected, mixed $actual): void
    {
        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, array{0:mixed,1:mixed}>
     */
    public static function staticProvider(): array
    {
        $object = new \stdClass();

        return [
            'is_array' => [is_array([]), Type::isArray([])],
            'is_bool' => [is_bool(true), Type::isBool(true)],
            'is_float' => [is_float(1.5), Type::isFloat(1.5)],
            'is_int' => [is_int(10), Type::isInt(10)],
            'is_null' => [is_null(null), Type::isNull(null)],
            'is_numeric' => [is_numeric('42.5'), Type::isNumeric('42.5')],
            'is_object' => [is_object($object), Type::isObject($object)],
            'is_scalar' => [is_scalar('x'), Type::isScalar('x')],
            'is_string' => [is_string('x'), Type::isString('x')],
            'gettype' => [gettype($object), Type::gettype($object)],
            'get_debug_type' => [get_debug_type($object), Type::getDebugType($object)],
            'cast_int' => [(int) '42', Type::toInt('42')],
            'cast_float' => [(float) '42.5', Type::toFloat('42.5')],
            'cast_string' => [(string) 42, Type::toString(42)],
            'cast_bool' => [(bool) 1, Type::toBool(1)],
        ];
    }
}
