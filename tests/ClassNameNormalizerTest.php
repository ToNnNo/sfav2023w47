<?php
declare(strict_types=1);

namespace App\Tests;

use App\Service\ClassNameNormalizer;
use PHPUnit\Framework\TestCase;

class ClassNameNormalizerTest extends TestCase
{
    /**
     * @dataProvider valuesProvider
     */
    public function testNormalize($value, $expected): void
    {
        $classNormalizer = new ClassNameNormalizer();
        $result = $classNormalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function valuesProvider(): array
    {
        return [
            ["hello world", "HelloWorld"],
            ["hello_world", "HelloWorld"],
            ["!hello", "Hello"],
            ["   hello    ", "Hello"],
            ["héllo", "Hello"],
            ["hello:world", "HelloWorld"],
            ["hello-world", "HelloWorld"],
            ["hello&world", "HelloWorld"],
            ["(hello)", "Hello"],
            ["{hello}", "Hello"],
            ["[hello]", "Hello"],
            ["\"hello\"", "Hello"],
            ["'hello'", "Hello"],
            ["&hello&", "Hello"],
            ["/hello/", "Hello"],
            ["\hello\\", "Hello"],
            ["#hello#", "Hello"],
        ];
    }

    /**
     * @dataProvider errorValuesProvider
     */
    public function testNormalizeError($value): void
    {
        $classNormalizer = new ClassNameNormalizer();

        $this->expectException(\TypeError::class);
        // $this->expectExceptionMessage("Argument #1 (\$name) must be of type string, null given");

        $classNormalizer->normalize($value);
    }

    public function errorValuesProvider(): array
    {
        return [ [null], [1], [false], [true], [-1], [0], [new \stdClass()] ];
        // [], [""],
    }
}
