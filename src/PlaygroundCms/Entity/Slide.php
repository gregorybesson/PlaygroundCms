<?php

namespace PlaygroundCms\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="cms_slideshow_slide")
 */
class Slide implements InputFilterAwareInterface
{

    protected $inputFilter;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Slideshow", inversedBy="slides")
     *
     **/
    protected $slideshow;
    
    /**
     * title
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;

    /**
     * subtitle
     * @ORM\Column(type="string", nullable=true)
     */
    protected $subtitle;

    /**
     * media
     * @ORM\Column(type="string", nullable=true)
     */
    protected $media;

    /**
     * link
     * @ORM\Column(type="string", nullable=true)
     */
    protected $link;

    /**
     * link
     * @ORM\Column(type="string", nullable=true)
     */
    protected $linkText;

    /**
     * videos
     * @ORM\Column(type="string", nullable=true, length=10000)
     */
    protected $video;

    /**
     * type
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $type = 0;

    /**
     * position
     * @ORM\Column(type="integer")
     */
    protected $position;

    /**
     * description
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;


    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return Slide
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Slideshow $slideshow
     * @return Slide
     */
    public function setSlideshow($slideshow)
    {
        $this->slideshow = $slideshow;

        return $this;
    }

    /**
     * @return int $slideshow
     */
    public function getSlideshow()
    {
        return $this->slideshow;
    }

    /**
     * @param string $title
     * @return Slide
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $subtitle
     * @return Slide
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $media
     * @return Slide
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return string $media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param string $link
     * @return Slide
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Slide
     */
    public function setLinkText($link)
    {
        $this->linkText = $linkText;

        return $this;
    }

    /**
     * @return string $link
     */
    public function getLinkText()
    {
        return $this->linkText;
    }

     /**
     * @param string $title
     * @return Content
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string $title
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $position
     * @return Slide
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $video
     * @return Video
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return string $video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param integer $active
     * @return Slide
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    
    /**
     * @return integer $type
     */
    public function getType()
    {
        return $this->type;
    }

     /** @PrePersist */
    public function createChrono()
    {
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");
    }

    /** @PreUpdate */
    public function updateChrono()
    {
        $this->updatedAt = new \DateTime("now");
    }


    /**
     * @return the unknown_type
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param unknown_type $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param unknown_type $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

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
        if (isset($data['title']) && $data['title'] != null) {
            $this->title = $data['title'];
        }

        if (isset($data['subtitle'])) {
            $this->subtitle = $data['subtitle'];
        }
        
        if (isset($data['type']) && $data['type'] != null) {
            $this->type = $data['type'];
        }

        if (isset($data['media']) && $data['media'] != null) {
            $this->media = $data['media'];
        }

        if (isset($data['position']) && $data['position'] != null) {
            $this->position = $data['position'];
        }

        if (isset($data['description'])) {
            $this->description = $data['description'];
        }

        if (isset($data['link'])) {
            $this->link = $data['link'];
        }

        if (isset($data['linkText'])) {
            $this->linkText = $data['linkText'];
        }
    }

    /**
    * getInputFilter
    *
    * @return  InputFilter $inputFilter
    */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used" .$inputFilter);
    }
}
