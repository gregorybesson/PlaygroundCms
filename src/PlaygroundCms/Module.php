<?php

namespace PlaygroundCms;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


        $options = $serviceManager->get('playgroundcore_module_options');
        $translator = $serviceManager->get('translator');
        $locale = $options->getLocale();
        if (!empty($locale)) {
            //translator
            $translator->setLocale($locale);

            // plugins
            $translate = $serviceManager->get('viewhelpermanager')->get('translate');
            $translate->getTranslator()->setLocale($locale);
        }

        AbstractValidator::setDefaultTranslator($translator, 'playgroundcms');
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            /*'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),*/
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'playgroundcms_doctrine_em' => 'doctrine.entitymanager.orm_default',
                'playgroundcms_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
            ),

            'invokables' => array(
                'playgroundcms_block_service'     => 'PlaygroundCms\Service\Block',
                'playgroundcms_dynablock_service' => 'PlaygroundCms\Service\Dynablock',
                'playgroundcms_page_service'      => 'PlaygroundCms\Service\Page',
                'playgroundcms_slideshow_service' => 'PlaygroundCms\Service\Slideshow',
                'playgroundcms_slide_service'     => 'PlaygroundCms\Service\Slide',
            ),

            'factories' => array(
                'playgroundcms_module_options' => function ($sm) {
                    $config = $sm->get('Configuration');

                    return new Options\ModuleOptions(
                        isset($config['playgroundcms']) ? $config['playgroundcms'] : array()
                    );
                },

                'playgroundcms_page_hydrator' => function () {
                    $hydrator = new Mapper\PageHydrator();

                    return $hydrator;
                },

                'playgroundcms_page_mapper' => function ($sm) {
                    $mapper = new Mapper\Page(
                        $sm->get('playgroundcms_doctrine_em'),
                        $sm->get('playgroundcms_module_options')
                    );
                    $mapper->setHydrator($sm->get('playgroundcms_page_hydrator'));

                    return $mapper;
                },

                'playgroundcms_block_hydrator' => function () {
                    $hydrator = new Mapper\BlockHydrator();

                    return $hydrator;
                },

                'playgroundcms_block_mapper' => function ($sm) {
                    $mapper = new Mapper\Block(
                        $sm->get('playgroundcms_doctrine_em'),
                        $sm->get('playgroundcms_module_options')
                    );
                    $mapper->setHydrator($sm->get('playgroundcms_block_hydrator'));

                    return $mapper;
                },

                'playgroundcms_dynablock_mapper' => function ($sm) {
                    $mapper = new Mapper\Dynablock(
                        $sm->get('playgroundcms_doctrine_em'),
                        $sm->get('playgroundcms_module_options')
                    );

                    return $mapper;
                },

                'playgroundcms_page_form' => function ($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Page(null, $sm, $translator);
                    $page = new Entity\Page();
                    $form->setInputFilter($page->getInputFilter());

                    return $form;
                },

                'playgroundcms_block_form' => function ($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Block(null, $translator);

                    return $form;
                },

                'playgroundcms_dynablock_form' => function ($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Dynablock(null, $translator);

                    return $form;
                },

                'playgroundcms_slideshow_form' => function ($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Slideshow(null, $sm, $translator);

                    return $form;
                },

                'playgroundcms_slideshow_mapper' => function ($sm) {

                    return new Mapper\Slideshow(
                        $sm->get('playgroundcms_doctrine_em'),
                        $sm->get('playgroundcms_module_options')
                    );
                },

                'playgroundcms_slide_form' => function ($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Slide(null, $sm, $translator);

                    return $form;
                },

                'playgroundcms_slide_mapper' => function ($sm) {

                    return new Mapper\Slide(
                        $sm->get('playgroundcms_doctrine_em'),
                        $sm->get('playgroundcms_module_options')
                    );
                },
            ),
        );
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'playgroundBlock' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\Block;
                    $viewHelper->setBlockMapper($locator->get('playgroundcms_block_mapper'));

                    return $viewHelper;
                },
                'playgroundDynablock' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\Dynablock;
                    $viewHelper->setBlockMapper($locator->get('playgroundcms_block_mapper'));
                    $viewHelper->setDynablockMapper($locator->get('playgroundcms_dynablock_mapper'));

                    return $viewHelper;
                },
                // This admin navigation layer gives the authentication layer based on BjyAuthorize ;)
                'adminMenu' => function ($sm) {
                    $nav = $sm->get('navigation')->menu('admin_navigation');
                    $serviceLocator = $sm->getServiceLocator();
                    $acl = $serviceLocator->get('BjyAuthorize\Service\Authorize')->getAcl();
                    $role = $serviceLocator->get('BjyAuthorize\Service\Authorize')->getIdentity();
                    $nav->setAcl($acl)
                    ->setRole($role)
                    ->setUseAcl()
                    ->setUlClass('nav')
                    ->setMaxDepth(10)
                    ->setRenderInvisible(false);

                    return $nav;
                },
                'slideshowWidget' => function ($sm) {
                    $slideshowService = $sm->getServiceLocator()->get('playgroundcms_slideshow_service');
                    return new \PlaygroundCms\View\Helper\Slideshow($slideshowService);
                }
            ),
        );
    }
}
