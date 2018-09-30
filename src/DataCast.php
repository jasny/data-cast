<?php

declare(strict_types=1);

namespace Jasny\MetaCast;

use function Jasny\expect_type;

/**
 * Perform type casting
 */
class DataCast
{
    /**
     * Type cast handlers for class properties
     * @var array
     **/
    protected $handlers;

    /**
     * Create class instance
     *
     * @param array $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * Cast data using handlers
     *
     * @param array|object $data
     * @return array|object
     */
    public function cast($data)
    {
        expect_type($data, ['array', 'object']);

        if ($isArray = is_array($data)) {
            $data = (object)$data;
        }

        $data = clone $data;

        foreach ($this->handlers as $name => $handler) {
            if (isset($data->$name)) {
                $data->$name = $handler->cast($data->$name);
            }
        }

        return $isArray ? (array)$data : $data;
    }
}
