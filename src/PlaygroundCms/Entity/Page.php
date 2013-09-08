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
 * @ORM\Table(name="cms_page")
 */
class Page implements PageInterface, InputFilterAwareInterface
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
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $identifier;

    /**
     * @ORM\Column(name="main_image", type="string", length=255, nullable=true)
     */
    protected $mainImage;

    /**
     * @ORM\Column(name="second_image", type="string", length=255, nullable=true)
     */
    protected $secondImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_keywords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;
	
	/**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $heading;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort_order = 0;

    /**
     * @ORM\Column(name="push_home",type="boolean", nullable=true)
     */
    protected $pushHome = 0;

    /**
     * @ORM\Column(name="display_home",type="boolean", nullable=true)
     */
    protected $displayHome = 0;

    /**
     * @ORM\Column(name="publication_date", type="datetime", nullable=true)
     */
    protected $publicationDate;

    /**
     * @ORM\Column(name="close_date", type="datetime", nullable=true)
     */
    protected $closeDate;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at",type="datetime")
     */
    protected $updatedAt;
    
	/**
     * @ORM\Column(name="category",type="integer", nullable=true)
     */
    protected $category = 0;

    /**
     * @param $id
     * @return Page|mixed
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
     * @param $identifier
     * @return Page
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
     * @param $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
	
	/**
     * @param $heading
     * @return Page
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     *
     * @return the $mainImage
     */
    public function getMainImage ()
    {
        return $this->mainImage;
    }

    /**
     *
     * @param field_type $mainImage
     */
    public function setMainImage ($mainImage)
    {
        $this->mainImage = $mainImage;
    }

    /**
     *
     * @return the $secondImage
     */
    public function getSecondImage ()
    {
        return $this->secondImage;
    }

    /**
     *
     * @param field_type $secondImage
     */
    public function setSecondImage ($secondImage)
    {
        $this->secondImage = $secondImage;
    }

    /**
     * @param $createdAt
     * @return Page
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $active
     * @return Page
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param $meta_description
     * @return Page
     */
    public function setMetaDescription($meta_description)
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * @param $meta_keywords
     * @return Page
     */
    public function setMetaKeywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * @param $sort_order
     * @return Page
     */
    public function setSortOrder($sort_order)
    {
        $this->sort_order = $sort_order;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sort_order;
    }

    /**
     *
     * @return the $pushHome
     */
    public function getPushHome ()
    {
        return $this->pushHome;
    }

    /**
     *
     * @param field_type $pushHome
     */
    public function setPushHome ($pushHome)
    {
        $this->pushHome = $pushHome;
    }

    /**
     *
     * @return the $displayHome
     */
    public function getDisplayHome ()
    {
        return $this->displayHome;
    }

    /**
     *
     * @param field_type $displayHome
     */
    public function setDisplayHome ($displayHome)
    {
        $this->displayHome = $displayHome;
    }

    /**
     *
     * @return the $publicationDate
     */
    public function getPublicationDate ()
    {
        return $this->publicationDate;
    }

    /**
     *
     * @param field_type $publicationDate
     */
    public function setPublicationDate ($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     *
     * @return the $closeDate
     */
    public function getCloseDate ()
    {
        return $this->closeDate;
    }

    /**
     *
     * @param field_type $closeDate
     */
    public function setCloseDate ($closeDate)
    {
        $this->closeDate = $closeDate;
    }

    /**
     * @param $updatedAt
     * @return Page
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
	
	/**
     * @param $category
     * @return Page
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
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
        /*$this->id = $data['id'];
         $this->username = $data['username'];
        $this->email = $data['email'];
        $this->displayName = $data['displayName'];
        $this->password = $data['password'];
        $this->state = $data['state'];*/
    }

    public function setInputFilter (InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter ()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'Int'
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'title',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'max' => 255
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                    'name' => 'publicationDate',
                    'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                    'name' => 'closeDate',
                    'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'identifier',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'PlaygroundCore\Filter\Slugify'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 255
                        )
                    )
                )
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
