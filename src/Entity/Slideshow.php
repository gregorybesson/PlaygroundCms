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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="cms_slideshow")
 * @Gedmo\TranslationEntity(class="PlaygroundCms\Entity\CmsTranslation")
 */
class Slideshow implements InputFilterAwareInterface, Translatable
{

    protected $inputFilter;

    protected $locale;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * title
     * @Gedmo\Translatable
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;

    /**
     * subtitle
     * @Gedmo\Translatable
     * @ORM\Column(type="string", nullable=true)
     */
    protected $subtitle;

    /**
     * active
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active = 0;

     /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Slide", mappedBy="slideshow", cascade={"persist","remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $slides;

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
        $this->slides = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return Slideshow
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
     * @param string $title
     * @return Slideshow
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
     * @return Slideshow
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
     * @param boolean $active
     * @return Slide
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return boolean $active
     */
    public function getActive()
    {
        return $this->active;
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

     /**
     * Get slides.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * Add a slide to the slideshow.
     *
     * @param Slide $slide
     *
     * @return void
     */
    public function addSlide($slide)
    {
        $this->slides[] = $slide;
    }

     /**
     * Remove all slides to the slideshow.
     *
     * @param Slide $slide
     *
     * @return void
     */
    public function removeSlides()
    {
        $this->slides = new ArrayCollection();

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

        if (isset($data['subtitle']) && $data['subtitle'] != null) {
            $this->subtitle = $data['subtitle'];
        }

        if (isset($data['active']) && $data['active'] != null) {
            $this->active = $data['active'];
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
    
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
