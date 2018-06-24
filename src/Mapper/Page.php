<?php

namespace PlaygroundCms\Mapper;

use Doctrine\ORM\EntityManager;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use PlaygroundCms\Options\ModuleOptions;
use Zend\Hydrator\HydratorInterface;

class Page implements PageInterface
{
    protected $tableName  = 'cms_page';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \PlaygroundCms\Options\ModuleOptions
     */
    protected $options;

    /**
     * @var EventManagerInterface
     */
    protected $event;

    protected $hydrator;

    public function __construct(EntityManager $em, ModuleOptions $options)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    public function setEventManager(\Zend\EventManager\SharedEventManager $events)
    {
        $this->event = new EventManager($events, [get_class($this)]);

        return $this;
    }

    public function getEventManager()
    {
        return $this->event;
    }

    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ClassMethods(false);
        }
        return $this->hydrator;
    }

    /**
     * @param HydratorInterface $hydrator
     * @return AbstractDbMapper
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    public function findAll()
    {
        $er = $this->em->getRepository($this->options->getPageEntityClass());

        return $er->findAll();
    }
    
    public function findAllBy($sortArray = array())
    {
        $er = $this->em->getRepository($this->options->getPageEntityClass());

        return $er->findBy(array(), $sortArray);
    }

    /**
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        $er = $this->em->getRepository($this->options->getPageEntityClass());
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
        $er = $this->em->getRepository($this->options->getPageEntityClass());
        $entity = $er->findOneBy(array('identifier' => $identifier));

        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));

        return $entity;
    }

    public function findByIsActive()
    {
        $er = $this->em->getRepository($this->options->getPageEntityClass());
        $entity = $er->findBy(array('active' => 1));

        $this->getEventManager()->trigger('find', $this, array('entity' => $entity));

        return $entity;
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
}
