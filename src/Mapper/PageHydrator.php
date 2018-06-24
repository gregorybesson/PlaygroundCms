<?php

namespace PlaygroundCms\Mapper;

use Zend\Hydrator\ClassMethods;
use PlaygroundCms\Entity\PageInterface as PageEntityInterface;

class PageHydrator extends ClassMethods
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
        if (!$object instanceof PageEntityInterface) {
            throw new Exception\InvalidArgumentException(
                '$object must be an instance of PlaygroundCms\Entity\PageInterface'
            );
        }

        /* @var $object PageInterface*/
        $data = parent::extract($object);
        $data = $this->mapField('id', 'page_id', $data);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                              $data
     * @param  object                             $object
     * @return PageInterface
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof PageEntityInterface) {
            throw new Exception\InvalidArgumentException(
                '$object must be an instance of PlaygroundCms\Entity\PageInterface'
            );
        }
        $data = $this->mapField('page_id', 'id', $data);

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
