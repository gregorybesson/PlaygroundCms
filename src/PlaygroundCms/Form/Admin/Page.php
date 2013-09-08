<?php

namespace PlaygroundCms\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\I18n\Translator\Translator;
use PlaygroundCore\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\ServiceManager\ServiceManager;

class Page extends ProvidesEventsForm
{
    protected $serviceManager;

    public function __construct($name = null, ServiceManager $sm, Translator $translator)
    {
        $this->setServiceManager($sm);
        $entityManager = $sm->get('playgroundcms_doctrine_em');
        $hydrator = new DoctrineHydrator($entityManager, 'PlaygroundCms\Entity\Page');
        $this->setHydrator($hydrator);

        parent::__construct();
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                    'value' => 0
            )
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

        // Adding an empty upload field to be able to correctly handle this on
        // the service side.
        $this->add(array(
                'name' => 'uploadMainImage',
                'attributes' => array(
                        'type' => 'file'
                ),
                'options' => array(
                        'label' => $translator->translate('Main image', 'playgroundcms')
                )
        ));
        $this->add(array(
                'name' => 'mainImage',
                'type' => 'Zend\Form\Element\Hidden',
                'attributes' => array(
                        'value' => ''
                )
        ));

        $this->add(array(
                'name' => 'uploadSecondImage',
                'attributes' => array(
                        'type' => 'file'
                ),
                'options' => array(
                        'label' => $translator->translate('Secondary image', 'playgroundcms')
                )
        ));
        $this->add(array(
                'name' => 'secondImage',
                'type' => 'Zend\Form\Element\Hidden',
                'attributes' => array(
                        'value' => ''
                )
        ));

        $this->add(array(
                'name' => 'sortOrder',
                'options' => array(
                    'label' => 'sort_order',
                ),
                'attributes' => array(
                    'type' => 'int'
                ),
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\DateTime',
                'name' => 'publicationDate',
                'options' => array(
                        'label' => $translator->translate('Date de publication', 'playgroundcms'),
                        'format' => 'd/m/Y'
                ),
                'attributes' => array(
                        'type' => 'text',
                        'class'=> 'date'
                )
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\DateTime',
                'name' => 'closeDate',
                'options' => array(
                    'label' => $translator->translate('Date of close', 'playgroundcms'),
                    'format' => 'd/m/Y'
                ),
                'attributes' => array(
                    'type' => 'text',
                    'class'=> 'date'
                )
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'displayHome',
                'options' => array(
                    'label' => 'Publier cet article sur la home',
                ),
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'pushHome',
                'options' => array(
                    'label' => 'Publier cet article sur le slider Home',
                ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'active',
            'options' => array(
                'value_options' => array(
                    '0' => $translator->translate('Non', 'playgroundcms'),
                    '1' => $translator->translate('Oui', 'playgroundcms')
                ),
                'label' => $translator->translate('Actif', 'playgroundcms')
            )
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'content',
                'options' => array(
                    'label' => $translator->translate('Bloc de contenu', 'playgroundcms')
                ),
                'attributes' => array(
                    'cols' => '10',
                    'rows' => '10',
                    'id' => 'block_content'
                )
        ));
		
		$this->add(array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'heading',
                'options' => array(
                    'label' => $translator->translate('Heading\'s article', 'playgroundcms')
                ),
                'attributes' => array(
                    'cols' => '5',
                    'rows' => '5',
                    'id' => 'block_heading'
                )
        ));
		
		$this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'category',
            'options' => array(
                'value_options' => array(
                    '0' => $translator->translate('Les gagnants', 'playgroundcms'),
                    '1' => $translator->translate('Autres', 'playgroundcms')
                ),
                'label' => $translator->translate('Catégorie', 'playgroundcms')
            )
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
        ->setLabel('Create')
        ->setAttributes(array(
            'type'  => 'submit',
        ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}
