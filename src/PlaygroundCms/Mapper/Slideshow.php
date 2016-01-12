<?php

namespace PlaygroundCms\Mapper;

use Doctrine\ORM\EntityManager;
use ZfcBase\Mapper\AbstractDbMapper;

class Slideshow extends EntityMapper
{
    public function getActiveSlideshowForFront($cat)
    {
        $select = " SELECT s.id ";
        $from  = " FROM PlaygroundCms\Entity\Slideshow s";
        $where = " WHERE s.active = 1 ";
        $orderBy = " ORDER BY s.updatedAt DESC ";
        
        $query = $select.' '.$from.' '.$where.' '.$orderBy;

        $slideshow =  $this->em->createQuery($query)->getResult();
        
        if (count($slideshow) == 0) {
            return false;
        }
        return $this->findById($slideshow[0]['id']);
    }

    public function getSlideshowForFront($locale)
    {
        $currentDate = new \DateTime('NOW');
        $select  = " SELECT s.id ";
        $from    = " FROM PlaygroundCms\Entity\Slideshow s";
        $orderBy = " ORDER BY s.updatedAt DESC ";
        
        $query = $select.' '.$from.' '.$orderBy;

        $slideshow =  $this->em->createQuery($query)->getResult();
        
        if (count($slideshow) == 0) {
            return false;
        }
        return $this->findById($slideshow[0]['id']);
    }

    public function getSlideshows()
    {
        $currentDate = new \DateTime('NOW');
        $select  = " SELECT s.id ";
        $from    = " FROM PlaygroundCms\Entity\Slideshow s ";
        $where   = " WHERE s.id > 0 ";
        $orderBy = " ORDER BY s.updatedAt DESC ";
        
        $query = $select.' '.$from.' '.$where.' '.$orderBy;

        $slideshowsTmp =  $this->em->createQuery($query)->getResult();

        if (count($slideshowsTmp) == 0) {
            return false;
        }
        foreach ($slideshowsTmp as $slideshow) {
            $slideshows[] = $slideshow['id'];
        }
        return $this->findBy(array('id' => $slideshows));
    }

    /**
    * getEntityRepository : recupere l'entite slideshow
    *
    * @return PlaygroundCms\Entity\Slideshow $slideshow
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCms\Entity\Slideshow');
        }

        return $this->er;
    }

    public function setSlideService($slideService)
    {
        $this->slideService = $slideService;

        return $this;
    }
}
