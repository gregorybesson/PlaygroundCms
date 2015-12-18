<?php

namespace PlaygroundCms\Mapper;

use Doctrine\ORM\EntityManager;
use ZfcBase\Mapper\AbstractDbMapper;
use PlaygroundCms\Options\ModuleOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

class Dynablock extends AbstractDbMapper implements DynablockInterface
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \PlaygroundCms\Options\ModuleOptions
     */
    protected $options;

    public function __construct(EntityManager $em, ModuleOptions $options)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    public function findAll()
    {
        $er = $this->em->getRepository($this->options->getDynablockEntityClass());

        return $er->findAll();
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->em->getRepository($this->options->getDynablockEntityClass());
        $entity = $er->find($id);

        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));

        return $entity;
    }

    /**
     * @param $identifier
     * @return object
     */
    public function findByIdentifier($identifier)
    {
        $er = $this->em->getRepository($this->options->getDynablockEntityClass());
        $entity = $er->findOneBy(array('identifier' => $identifier));

        return $entity;
    }

    /**
     * Find entity by integer id or string identifier
     *
     * @param $identifier
     * @return object
     * @throws \Exception
     */
    public function findByDynarea($dynarea)
    {
        $er = $this->em->getRepository($this->options->getDynablockEntityClass());

        $entities = $er->findBy(array('dynarea' => $dynarea), array('position' => 'ASC'));

        return $entities;
    }

    /**
     *
     *
     * @param $identifier
     * @return object
     * @throws \Exception
     */
    public function findActiveDynablocks()
    {
        $er = $this->em->getRepository($this->options->getDynablockEntityClass());

        $entities = $er->findBy(array(), array('is_active' => 'DESC', 'dynarea' => 'ASC'));

        return $entities;
    }

    /**
     * Find entity by integer id or string identifier
     *
     * @param $identifier
     * @return object
     * @throws \Exception
     */
    public function find($identifier)
    {
        if (is_int($identifier)) {
            $entity = $this->findById($identifier);
        } elseif (is_string($identifier)) {
            $entity = $this->findByIdentifier($identifier);
        } else {
            throw new \Exception('Wrong block identifier provided.');
        }

        return $entity;
    }
    public function clear($dynarea)
    {
        $dynablocks = $this->findByDynarea($dynarea);

        foreach ($dynablocks as $dynablock) {
            $this->remove($dynablock);
        }

        return true;
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
}
