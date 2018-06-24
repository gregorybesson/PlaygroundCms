<?php

namespace PlaygroundCms\Mapper;

use Doctrine\ORM\EntityManager;

use PlaygroundCms\Options\ModuleOptions;

class EntityMapper
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $er;

    /**
     * @var \PlaygroundCms\Options\ModuleOptions
     */
    protected $options;


    /**
    * __construct
    * @param Doctrine\ORM\EntityManager $em
    * @param PlaygroundCms\Options\ModuleOptions $options
    *
    */
    public function __construct(EntityManager $em, $options)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    /**
    * findById : recupere l'entite en fonction de son id
    * @param int $id
    *
    * @return
    */
    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $filter tableau de filtre
    *
    * @return
    */
    public function findBy($filter, $order = null, $limit = null, $offset = null)
    {
        return $this->getEntityRepository()->findBy($filter, $order, $limit, $offset);
    }

    public function findByAndOrderBy($by = array(), $sortArray = array())
    {
        return $this->getEntityRepository()->findBy($by, $sortArray);
    }

    /**
    * insert : insert en base une entité contact
    * @param PlaygroundCms\Entity\Contact $contact contact
    *
    * @return PlaygroundCms\Entity\Contact $contact
    */
    public function insert($entity)
    {
        return $this->persist($entity);
    }

    /**
    * insert : met a jour en base une entité contact
    * @param PlaygroundCms\Entity\Contact $contact contact
    *
    * @return PlaygroundCms\Entity\Contact $contact
    */
    public function update($entity)
    {
        return $this->persist($entity);
    }

     /**
    * findBy : recupere des entites en fonction de filtre
    * @param array $array tableau de filtre
    *
    * @return collection $headlines collection de Citoren\Entity\MediaArbo
    */
    public function findOneBy($array)
    {
        return $this->getEntityRepository()->findOneBy($array);
    }

    /**
    * insert : met a jour en base une entité company et persiste en base
    * @param PlaygroundCms\Entity\Contact $entity contact
    *
    * @return PlaygroundCms\Entity\Contact $contact
    */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
    * findAll : recupere toutes les entites
    *
    * @return collection $contact collection de PlaygroundCms\Entity\Contact
    */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

     /**
    * remove : supprimer une entite contact
    * @param PlaygroundCms\Entity\Contact $contact Contact
    *
    */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
