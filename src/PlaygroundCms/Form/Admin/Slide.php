<?php

namespace PlaygroundCms\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\ServiceManager;

class Slide extends ProvidesEventsForm
{

    protected $serviceManager;
    protected $slideService;

    public function __construct($name = null, ServiceManager $sm, Translator $translator)
    {
        parent::__construct($name);
        $this->setServiceManager($sm);

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));

        $this->add(array(
            'name' => 'slideshowId',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => null,
            )
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Title', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Title', 'playgroundcms'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Zend\Validator\NotEmpty'),
            )
        ));

        $this->add(array(
            'name' => 'subtitle',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Subtitle', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Subtitle', 'playgroundcms'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Zend\Validator\NotEmpty'),
            )
        ));
        $this->add(array(
            'name' => 'link',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Link', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Link', 'playgroundcms'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Zend\Validator\NotEmpty'),
            )
        ));

        $this->add(array(
            'name' => 'linkText',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('LinkText', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('LinkText', 'playgroundcms'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Zend\Validator\NotEmpty'),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'options' => array(
                'label' => $translator->translate('Description', 'playgroundcms')
            ),
            'attributes' => array(
                'id' => 'headlineDescription',
                'class' => 'form-control',
                'rows' => 5,
            )
         ));

        $this->add(array(
            'name' => 'position',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Position', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Position', 'playgroundcms'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Zend\Validator\NotEmpty'),
            )
         ));

        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'uploadFile',
            'options' => array(
                'label' => $translator->translate('Media', 'playgroundcms'),
            ),
            'attributes' => array(
            ),
         ));

        $this->add(array(
            'name' => 'media',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => '',
            ),
         ));

        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => $translator->translate('Type', 'playgroundcms'),
                'value_options' => array(
                    '0' => 'Video',
                    '1' => 'Picture',
                )
            ),
            'attributes' => array(
                'type' => 'radio',
                'class' => 'type'
            ),
         ));

        $submitElement = new Element\Button('submit');
        $submitElement->setAttributes(array(
            'type' => 'submit',
            'class'=> 'btn btn-success'
         ));
        $this->add($submitElement, array());
    }

    public function getSlideStatuses()
    {
        return $this->getSlideService()->getStatuses();
    }

    public function getSlideService()
    {
        if (null === $this->slideService) {
            $this->slideService = $this->getServiceManager()->get('playgroundcms_slide_service');
        }

        return $this->slideService;
    }

    public function setSlideService($slideService)
    {
        $this->slideService = $slideService;

        return $this;
    }

     /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
