<?php

namespace PlaygroundCms\Mapper;

use Doctrine\ORM\EntityManager;
use ZfcBase\Mapper\AbstractDbMapper;

class Slide extends EntityMapper
{
    public function getActiveSlideshowForFront()
    {
        $select = " SELECT slide.id ";
        $from = " FROM PlaygroundCms\Entity\Slideshow s INNER JOIN s.slides slide";
        $where = " AND s.active = 1 ";
        $orderBy = " ORDER BY s.updatedAt DESC ";

        $query = $select.' '.$from.' '.$where.' '.$orderBy;

        $slidesTmp =  $this->em->createQuery($query)->getResult();

        if (count($slidesTmp) == 0) {
            return false;
        }
        foreach ($slidesTmp as $slide) {
            $slides[] = $slide['id'];
        }

        return $this->findBy(array('id' => $slides), array('position' => 'ASC'));
    }

    public function getActiveSlideshowForBlock($id)
    {
        $select  = " SELECT slide.id ";
        $from    = " FROM PlaygroundCms\Entity\Slideshow s INNER JOIN s.slides slide";
        $where  = " WHERE s.id = '" . $id . "' ";
        $where .= " AND s.active = 1 ";
        $orderBy = " ORDER BY s.updatedAt DESC ";
        
        $query = $select.' '.$from.' '.$where.' '.$orderBy;
        $slidesTmp =  $this->em->createQuery($query)->getResult();
        if (count($slidesTmp) == 0) {
            return false;
        }
        foreach ($slidesTmp as $slide) {
            $slides[] = $slide['id'];
        }
        return $this->findBy(array('id' => $slides), array('position' => 'ASC'));
    }
 
    /**
    * getEntityRepository : recupere l'entite slide
    *
    * @return PlaygroundCms\Entity\Slide $slide
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCms\Entity\Slide');
        }

        return $this->er;
    }
}
