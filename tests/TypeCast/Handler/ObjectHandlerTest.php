<?php

namespace Jasny\MetaCast\Tests\TypeCast\Handler;

use Jasny\MetaCast\MetaCast;
use Jasny\MetaCast\TypeCast\Handler\ObjectHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers Jasny\MetaCast\TypeCast\Handler\ObjectHandler
 */
class ObjectHandlerTest extends TestCase
{
    /**
     * Test 'cast' method in case when __set_state should be used
     */
    public function testCast()
    {
        $class = $this->getObjectClass();
        $data = ['x' => 'x_value', 'y' => 'y_value'];
        $casted = ['x' => 'casted_x_value', 'y' => 'casted_y_value'];

        $metaCast = $this->createMock(MetaCast::class);
        $metaCast->expects($this->once())->method('cast')->with($class, $data)->willReturn($casted);

        $handler = new ObjectHandler($metaCast);
        $handler = $handler->forType($class);

        $result = $handler->cast($data);

        $this->assertInstanceOf($class, $result);
        $this->assertSame($casted['x'], $result->x);
        $this->assertSame($casted['y'], $result->y);
    }

    /**
     * Get target class for casting
     *
     * @return string
     */
    protected function getObjectClass()
    {
        $object = new class() {
            public $x;
            public $y;

            public static function __set_state(array $data)
            {
                $foobar = new self();

                if (isset($data['x'])) $foobar->x = $data['x'];
                if (isset($data['y'])) $foobar->y = $data['y'];

                return $foobar;
            }
        };

        return get_class($object);
    }
}
