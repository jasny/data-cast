<?php

declare(strict_types=1);

namespace Jasny\MetaCast\TypeCast\Handler;

use Jasny\TypeCast\Handler\ObjectHandler as BaseObjectHandler;
use Jasny\TypeCast\HandlerInterface;
use Jasny\MetaCast\MetaCast;

/**
 * Type cast to an object of a specific class
 */
class ObjectHandler extends BaseObjectHandler
{
    /**
     * Instance of MetaCast
     * @var MetaCast
     **/
    protected $metaCast;

    /**
     * Create class instance
     *
     * @param MetaCast $metaCast
     */
    public function __construct(MetaCast $metaCast)
    {
        $this->metaCast = $metaCast;
    }

    /**
     * Create object using __set_state.
     *
     * @param mixed $value
     * @return object|mixed
     */
    protected function createWithSetState($value)
    {
        $value = $this->metaCast->cast($this->class, $value);

        return parent::createWithSetState($value);
    }
}
