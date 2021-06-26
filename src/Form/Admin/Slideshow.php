<?php

namespace PlaygroundCms\Form\Admin;

use Laminas\Form\Form;
use Laminas\Form\Element;
use ZfcUser\Form\ProvidesEventsForm;
use Laminas\Mvc\I18n\Translator;
use Laminas\ServiceManager\ServiceManager;

class Slideshow extends ProvidesEventsForm
{

    protected $serviceManager;
    protected $slideshowService;

    public function __construct($name, ServiceManager $sm, Translator $translator)
    {
        parent::__construct($name);
        $this->setServiceManager($sm);

        $this->add(array(
            'name' => 'id',
            'type' => 'Laminas\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'Laminas\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Title', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Title', 'playgroundcms'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Laminas\Validator\NotEmpty'),
            )
        ));

        $this->add(array(
            'name' => 'subtitle',
            'type' => 'Laminas\Form\Element\Text',
            'options' => array(
                'label' => $translator->translate('Subtitle', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Subtitle', 'playgroundcms'),
                'class' => 'form-control',
            ),
            'validator' => array(
                array('name' => 'Laminas\Validator\NotEmpty'),
            )
        ));

        $this->add(array(
            'type' => 'Laminas\Form\Element\Select',
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
