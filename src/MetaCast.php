<?php

declare(strict_types=1);

namespace Jasny\MetaCast;

use Jasny\Meta\FactoryInterface;
use Jasny\Meta\MetaClass;
use Jasny\TypeCastInterface;
use Psr\SimpleCache\CacheInterface;
use \InvalidArgumentException;
use function Jasny\expect_type;

/**
 * Cast data to class using class metadata
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
     * Cache for DataCast
     * @var CacheInterface
     **/
    protected $cache;

    /**
     * Create class instance
     *
     * @param FactoryInterface $metaFactory
     * @param TypeCastInterface $typeCast
     */
    public function __construct(FactoryInterface $metaFactory, TypeCastInterface $typeCast, CacheInterface $cache)
    {
        $this->metaFactory = $metaFactory;
        $this->typeCast = $typeCast;
        $this->cache = $cache;
    }

    /**
     * Use object as callable
     *
     * @param string|object $class
     * @param array|object $data
     * @return array|object
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
     * @return array|object
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

        $cacheName = 'DataCastForClass:' . $class;
        $caster = $this->cache->get($cacheName);

        if (!$caster instanceof DataCast) {
            $meta = $this->metaFactory->forClass($class);
            $handlers = $this->getHandlers($meta, $data);
            $caster = $this->getDataCaster($handlers);

            $this->cache->set($cacheName, $caster);
        }

        return $caster->cast($data);
    }

    /**
     * Get cast handlers
     *
     * @param MetaClass $meta
     * @param object $data
     * @return array
     */
    protected function getHandlers(MetaClass $meta, $data)
    {
        $handlers = [];
        $properties = $meta->getProperties();

        foreach ($properties as $name => $item) {
            $toType = $item->get('type');

            if ($toType) {
                $handlers[$name] = $this->typeCast->to($toType);
            }
        }

        return $handlers;
    }

    /**
     * Get instance of data caster
     *
     * @codeCoverageIgnore
     * @param array $handlers
     * @return DataCast
     */
    protected function getDataCaster(array $handlers)
    {
        return new DataCast($handlers);
    }
}
