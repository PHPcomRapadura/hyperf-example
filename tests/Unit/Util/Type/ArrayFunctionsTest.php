<?php

declare(strict_types=1);

namespace Tests\Unit\Util\Type;

use PHPUnit\Framework\TestCase;

use function Util\Type\Array\extractArray;
use function Util\Type\Array\extractBool;
use function Util\Type\Array\extractInt;
use function Util\Type\Array\extractString;

class ArrayFunctionsTest extends TestCase
{
    public function testExtractArrayReturnsArrayWhenPropertyExists(): void
    {
        $array = ['property' => ['key' => 'value']];
        $result = extractArray($array, 'property');
        $this->assertEquals($array['property'], $result);
    }

    public function testExtractArrayReturnsDefaultWhenPropertyDoesNotExist(): void
    {
        $array = [];
        $default = ['default'];
        $result = extractArray($array, 'property', $default);
        $this->assertEquals($default, $result);
    }

    public function testExtractStringReturnsStringWhenPropertyExists(): void
    {
        $array = ['property' => 'value'];
        $result = extractString($array, 'property');
        $this->assertEquals($array['property'], $result);
    }

    public function testExtractStringReturnsDefaultWhenPropertyDoesNotExist(): void
    {
        $array = [];
        $default = 'default';
        $result = extractString($array, 'property', $default);
        $this->assertEquals($default, $result);
    }

    public function testExtractIntReturnsIntWhenPropertyExists(): void
    {
        $array = ['property' => 123];
        $result = extractInt($array, 'property');
        $this->assertEquals($array['property'], $result);
    }

    public function testExtractIntReturnsDefaultWhenPropertyDoesNotExist(): void
    {
        $array = [];
        $default = 456;
        $result = extractInt($array, 'property', $default);
        $this->assertEquals($default, $result);
    }

    public function testExtractBoolReturnsBoolWhenPropertyExists(): void
    {
        $array = ['property' => true];
        $result = extractBool($array, 'property');
        $this->assertEquals($array['property'], $result);
    }

    public function testExtractBoolReturnsDefaultWhenPropertyDoesNotExist(): void
    {
        $array = [];
        $default = true;
        $result = extractBool($array, 'property', $default);
        $this->assertEquals($default, $result);
    }
}
