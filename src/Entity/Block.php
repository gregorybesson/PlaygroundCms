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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="cms_block")
 * @Gedmo\TranslationEntity(class="PlaygroundCms\Entity\CmsTranslation")
 */
class Block implements BlockInterface, InputFilterAwareInterface, Translatable
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
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $identifier;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="boolean")
     */
    protected $is_active;

    /**
     * Is this block available as Dynamic Blocks
     * @ORM\Column(type="boolean")
     */
    protected $on_call;

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
     * @return the unknown_type
     */
    public function getOnCall()
    {
        return $this->on_call;
    }

    /**
     * @param unknown_type $on_call
     */
    public function setOnCall($on_call)
    {
        $this->on_call = $on_call;

        return $this;
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
     * @param $title
     * @return Block
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
     * @return Block|mixed
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
        if (isset($data['is_active']) && $data['is_active'] != null) {
            $this->is_active = $data['is_active'];
        }
        if (isset($data['content']) && $data['content'] != null) {
            $this->content = $data['content'];
        }
        $this->identifier = (isset($data['identifier'])) ? $data['identifier'] : null;
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

            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'id',
                    'required' => true,
                    'filters' => array(array('name' => 'Int'))
                )
            ));

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'title',
                        'required' => true,
                        'filters' => array(array('name' => 'StripTags'), array('name' => 'StringTrim')),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 5
                                ),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'identifier',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'PlaygroundCore\Filter\Slugify')
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array('encoding' => 'UTF-8', 'min' => 5, 'max' => 255),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'is_active',
                        'required' => true,
                        'validators' => array(
                            array(
                                'name' => 'Between',
                                'options' => array('min' => 0, 'max' => 1)
                            )
                        )
                    )
                )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
