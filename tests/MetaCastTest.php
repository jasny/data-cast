<?php

namespace Jasny\MetaCast\Tests;

use Jasny\Meta\FactoryInterface;
use Jasny\Meta\MetaClass;
use Jasny\Meta\MetaProperty;
use Jasny\MetaCast\MetaCast;
use Jasny\TypeCastInterface;
use Jasny\TypeCast\HandlerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers Jasny\MetaCast\MetaCast
 */
class MetaCastTest extends TestCase
{
    /**
     * Set up dependencies before each test case
     */
    protected function setUp()
    {
        $this->metaFactory = $this->createMock(FactoryInterface::class);
        $this->typeCast = $this->createMock(TypeCastInterface::class);
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
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Can not cast '$type' to 'Foo': expected object or array");

        $metaCast = new MetaCast($this->metaFactory, $this->typeCast);
        $result = $metaCast->cast('Foo', $data);
    }

    /**
     * Test 'cast' method, if data is an instance of the same class
     */
    public function testCastSameClass()
    {
        $this->metaFactory->expects($this->never())->method('forClass');

        $data = (object)['foo' => 'bar'];
        $metaCast = new MetaCast($this->metaFactory, $this->typeCast);

        $result = $metaCast->cast(stdClass::class, $data);

        $this->assertEquals($data, $result);
        $this->assertNotSame($data, $result);
    }

    /**
     * Provide data for testing 'cast' method
     *
     * @return array
     */
    public function castProvider()
    {
        $data = [
            'foo' => 'value1',
            'bar' => 'value2',
            'baz' => 'value3'
        ];

        return [
            [$data],
            [(object)$data],
        ];
    }

    /**
     * Test 'cast' method
     *
     * @dataProvider castProvider
     */
    public function testCast($data)
    {
        $class = 'Foo';
        $meta = $this->createMock(MetaClass::class);
        $castHandler = $this->createMock(HandlerInterface::class);

        $property1 = $this->createMock(MetaProperty::class);
        $property2 = $this->createMock(MetaProperty::class);
        $property3 = $this->createMock(MetaProperty::class);
        $property4 = $this->createMock(MetaProperty::class);
        $property5 = $this->createMock(MetaProperty::class);

        $property1->expects($this->once())->method('get')->with('type')->willReturn('type1');
        $property2->expects($this->once())->method('get')->with('type')->willReturn(null);
        $property3->expects($this->once())->method('get')->with('type')->willReturn('type2');
        $property4->expects($this->once())->method('get')->with('type')->willReturn('type3');
        $property5->expects($this->once())->method('get')->with('type')->willReturn(null);

        $properties = [
            'foo' => $property1,
            'bar' => $property2,
            'zoo' => $property3,
            'baz' => $property4,
            'pir' => $property5
        ];

        $castedProperties = (object)[
            'foo' => 'casted_value1',
            'bar' => 'value2',
            'baz' => 'casted_value3'
        ];

        $expected = (object)$castedProperties;

        $this->metaFactory->expects($this->once())->method('forClass')->with($class)->willReturn($meta);
        $meta->expects($this->once())->method('getProperties')->willReturn($properties);

        $this->typeCast->expects($this->exactly(3))->method('to')
            ->withConsecutive(['type1'], ['type3'], [$class])
            ->willReturnOnConsecutiveCalls($castHandler, $castHandler, $castHandler);

        $castHandler->expects($this->exactly(3))->method('cast')
            ->withConsecutive(['value1'], ['value3'], [$castedProperties])
            ->willReturnOnConsecutiveCalls('casted_value1', 'casted_value3', $expected);

        $metaCast = new MetaCast($this->metaFactory, $this->typeCast);
        $result = $metaCast->cast('Foo', $data);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test 'cast' method, if reflection returns no properties
     */
    public function testCastNoProperties()
    {
        $class = 'Foo';
        $data = ['foo' => 'bar'];
        $meta = $this->createMock(MetaClass::class);
        $castHandler = $this->createMock(HandlerInterface::class);

        $expected = (object)$data;

        $this->metaFactory->expects($this->once())->method('forClass')->with($class)->willReturn($meta);
        $meta->expects($this->once())->method('getProperties')->willReturn([]);
        $this->typeCast->expects($this->once())->method('to')->with($class)->willReturn($castHandler);
        $castHandler->expects($this->once())->method('cast')->with((object)$data)->willReturn($expected);

        $metaCast = new MetaCast($this->metaFactory, $this->typeCast);
        $result = $metaCast->cast('Foo', $data);

        $this->assertEquals($expected, $result);
    }
}
