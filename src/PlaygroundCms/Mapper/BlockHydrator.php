<?php

namespace PlaygroundCms\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;
use PlaygroundCms\Entity\BlockInterface as BlockEntityInterface;

class BlockHydrator extends ClassMethods
{
    /**
     * Extract values from an object
     *
     * @param  object                             $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof BlockEntityInterface) {
            throw new Exception\InvalidArgumentException(
                '$object must be an instance of PlaygroundCms\Entity\BlockInterface'
            );
        }

        /* @var $object BlockInterface*/
        $data = parent::extract($object);
        //$data = $this->mapField('id', 'block_id', $data);
        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                              $data
     * @param  object                             $object
     * @return BlockInterface
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof BlockEntityInterface) {
            throw new Exception\InvalidArgumentException(
                '$object must be an instance of PlaygroundCms\Entity\BlockInterface'
            );
        }
        //$data = $this->mapField('block_id', 'id', $data);
        return parent::hydrate($data, $object);
    }

    /**
     * @param $keyFrom
     * @param $keyTo
     * @param  array $array
     * @return array
     */
    protected function mapField($keyFrom, $keyTo, array $array)
    {
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);

        return $array;
    }
}
