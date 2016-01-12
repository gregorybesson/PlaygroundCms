<?php

namespace PlaygroundCms\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\ServiceManager;

class Slideshow extends ProvidesEventsForm
{

    protected $serviceManager;
    protected $slideshowService;

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
            'type' => 'Zend\Form\Element\Select',
            'name' => 'active',
            'options' => array(
                'label' => $translator->translate('Status', 'playgroundcms'),
                'value_options' => $this->getSlideshowStatuses(),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement->setAttributes(array(
            'type' => 'submit',
            'class'=> 'btn btn-success'
        ));
        $this->add($submitElement, array());
    }

    public function getSlideshowStatuses()
    {
        return $this->getSlideshowService()->getStatuses();
    }

    public function getSlideshowService()
    {
        if (null === $this->slideshowService) {
            $this->slideshowService = $this->getServiceManager()->get('playgroundcms_slideshow_service');
        }

        return $this->slideshowService;
    }

    public function setSlideshowService($slideshowService)
    {
        $this->slideshowService = $slideshowService;

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
