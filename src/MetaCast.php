<?php

declare(strict_types=1);

namespace Jasny\MetaCast;

use Jasny\Meta\FactoryInterface;
use Jasny\Meta\MetaClass;
use Jasny\TypeCastInterface;
use \InvalidArgumentException;

/**
 * Cast data to class
 */
class MetaCast
{
    /**
     * Factory for fetching meta
     * @var FactoryInterface
     **/
    protected $metaFactory;

    /**
     * Type caster
     * @var TypeCastInterface
     **/
    protected $typeCast;

    /**
     * Create class instance
     *
     * @param FactoryInterface $metaFactory
     * @param TypeCastInterface $typeCast
     */
    public function __construct(FactoryInterface $metaFactory, TypeCastInterface $typeCast)
    {
        $this->metaFactory = $metaFactory;
        $this->typeCast = $typeCast;
    }

    /**
     * Cast data to given class
     *
     * @param string $class
     * @param array|object $data
     * @return object
     */
    public function cast(string $class, $data)
    {
        if (!is_array($data) && !is_object($data)) {
            $type = gettype($data);
            throw new InvalidArgumentException("Can not cast '$type' to '$class': expected object or array");
        }

        if (is_array($data)) {
            $data = (object)$data;
        }

        if (is_a($data, $class)) {
            return clone $data;
        }

        $meta = $this->metaFactory->forClass($class);
        $data = $this->castProperties($meta, $data);

        return $this->typeCast->to($class)->cast($data);
    }

    /**
     * Cast class properties
     *
     * @param MetaClass $meta
     * @param object $data
     * @return object
     */
    protected function castProperties(MetaClass $meta, $data)
    {
        $data = clone $data;
        $properties = $meta->getProperties();

        foreach ($properties as $name => $item) {
            $toType = $item->get('var');
            if (!$toType || !isset($data->$name)) {
                continue;
            }

            $data->$name = $this->typeCast->to($toType)->cast($data->$name);
        }

        return $data;
    }
}
