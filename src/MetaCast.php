<?php

declare(strict_types=1);

namespace Jasny\MetaCast;

use Jasny\Meta\FactoryInterface;
use Jasny\Meta\MetaClass;
use Jasny\TypeCastInterface;
use \InvalidArgumentException;
use function Jasny\expect_type;

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
     * Use object as callable
     *
     * @param string|object $class
     * @param array|object $data
     * @return object
     */
    final public function __invoke($class, $data)
    {
        return $this->cast($class, $data);
    }

    /**
     * Cast data to given class
     *
     * @param string|object $class
     * @param array|object $data
     * @return object
     */
    public function cast($class, $data)
    {
        expect_type($class, ['string', 'object']);
        expect_type($data, ['array', 'object']);

        if (is_object($class)) {
            $class = get_class($class);
        }

        if (is_a($data, $class)) {
            return clone $data;
        }

        $meta = $this->metaFactory->forClass($class);
        $data = $this->castProperties($meta, $data);

        return $data;
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
        $isArray = is_array($data);
        if ($isArray) {
            $data = (object)$data;
        }

        $data = clone $data;
        $properties = $meta->getProperties();

        foreach ($properties as $name => $item) {
            $toType = $item->get('type');

            if ($toType && isset($data->$name)) {
                $data->$name = $this->typeCast->to($toType)->cast($data->$name);
            }
        }

        return $isArray ? (array)$data : $data;
    }
}
