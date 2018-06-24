<?php

namespace PlaygroundCms\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcUser\Form\ProvidesEventsForm;
use Zend\Mvc\I18n\Translator;

class Block extends ProvidesEventsForm
{
    protected $serviceManager;

    public function __construct($name, Translator $translator)
    {
        //$this->setServiceManager($serviceManager);
        parent::__construct();

        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0,
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => $translator->translate('Title', 'playgroundcms'),
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
                'name' => 'identifier',
                'options' => array(
                        'label' => $translator->translate('identifier', 'playgroundcms'),
                ),
                'attributes' => array(
                        'type' => 'text'
                ),
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'is_active',
                'options' => array(
                    'value_options' => array(
                            '0' => $translator->translate('No', 'playgroundcms'),
                            '1' => $translator->translate('Yes', 'playgroundcms'),
                    ),
                    'label' => $translator->translate('Active', 'playgroundcms'),
                ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'on_call',
            'options' => array(
                 'label' => $translator->translate('This Block is available for DynaBlock', 'playgroundcms'),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'content',
            'options' => array(
                'label' => $translator->translate('Content', 'playgroundcms'),
            ),
            'attributes' => array(
                'cols' => '10',
                'rows' => '10',
                'id' => 'content',
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
        ->setAttributes(array(
                'type'  => 'submit',
        ));

        $this->add($submitElement, array(
                'priority' => -100,
        ));
    }

/*    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }*/
}
