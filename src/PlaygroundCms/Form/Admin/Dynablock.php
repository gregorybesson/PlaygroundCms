<?php

namespace PlaygroundCms\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\I18n\Translator\Translator;

class Dynablock extends ProvidesEventsForm
{
    protected $serviceManager;

    public function __construct($name = null, Translator $translator)
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
               'name' => 'dynarea',
               'type'  => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'title',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
                'name' => 'identifier',
                'options' => array(
                    'label' => 'identifier',
                ),
                'attributes' => array(
                    'type' => 'text'
                ),
        ));

        $this->add(array(
                'name' => 'position',
                'options' => array(
                        'label' => 'Position',
                ),
                'attributes' => array(
                        'type' => 'text'
                ),
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'type',
                'options' => array(
                        'value_options' => array(
                                '0' => $translator->translate('No', 'playgroundcms'),
                                '1' => $translator->translate('Yes', 'playgroundcms'),
                        ),
                        'label' => $translator->translate('Type', 'playgroundcms'),
                ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'is_active',
            'options' => array(
                 'label' => 'Is active',
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
