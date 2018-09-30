<?php

namespace Jasny\MetaCast\Tests;

use Jasny\Meta\FactoryInterface;
use Jasny\Meta\MetaClass;
use Jasny\Meta\MetaProperty;
use Jasny\MetaCast\MetaCast;
use Jasny\MetaCast\DataCast;
use Jasny\TypeCastInterface;
use Jasny\TypeCast\HandlerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers Jasny\MetaCast\DataCast
 */
class DataCastTest extends TestCase
{
    /**
     * Provide data for testing 'cast' method
     *
     * @return array
     */
    public function castProvider()
    {
        $data = [
            'foo' => 'foo_value',
            'baz' => 'baz_value'
        ];

        $expected = [
            'foo' => 'foo_casted',
            'baz' => 'baz_casted'
        ];

        return [
            [$data, $expected],
            [(object)$data, (object)$expected]
        ];
    }

    /**
     * Test 'cast' method
     *
     * @dataProvider castProvider
     */
    public function testCast($data, $expected)
    {
        $handlers = [
            'foo' => $this->createMock(HandlerInterface::class),
            'bar' => $this->createMock(HandlerInterface::class),
            'baz' => $this->createMock(HandlerInterface::class),
        ];

        $handlers['foo']->expects($this->once())->method('cast')->with('foo_value')->willReturn('foo_casted');
        $handlers['baz']->expects($this->once())->method('cast')->with('baz_value')->willReturn('baz_casted');

        $dataCast = new DataCast($handlers);
        $result = $dataCast->cast($data);

        $this->assertEquals($expected, $result);
    }

    /**
     * Provide data for testing 'cast' method for promitive values
     *
     * @return array
     */
    public function castPrimitiveProvider()
    {
        return [
            [12, 'integer'],
            ['foo', 'string'],
            [true, 'boolean'],
            [null, 'NULL'],
        ];
    }

    /**
     * Test 'cast' method for primitive value
     *
     * @dataProvider castPrimitiveProvider
     */
    public function testCastPrimitive($data, $type)
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("Expected array or object, $type given");

        $dataCast = new DataCast([]);
        $dataCast->cast($data);
    }
}
