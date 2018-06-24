<?php

namespace PlaygroundCms\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="cms_dynablock")
 */
class Dynablock implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $dynarea;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $identifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $is_active = 0;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $position = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /** @PrePersist */
    public function createChrono()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /** @PreUpdate */
    public function updateChrono()
    {
        $this->updated_at = new \DateTime("now");
    }

    /**
     * @param $id
     * @return Block|mixed
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $isActive
     * @return Block
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param $identifier
     * @return Block|mixed
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return the unknown_type
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param unknown_type $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getDynarea()
    {
        return $this->dynarea;
    }

    /**
     * @param unknown_type $dynarea
     */
    public function setDynarea($dynarea)
    {
        $this->dynarea = $dynarea;

        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param unknown_type $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param unknown_type $position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @param $createdAt
     * @return Block
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param $updatedAt
     * @return Block
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->dynarea = (isset($data['dynarea'])) ? $data['dynarea'] : null;
        if (isset($data['is_active']) && $data['is_active'] != null) {
            $this->is_active = $data['is_active'];
        }
        if (isset($data['position']) && $data['position'] != null) {
            $this->position = $data['position'];
        }
        $this->identifier = (isset($data['identifier'])) ? $data['identifier'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
