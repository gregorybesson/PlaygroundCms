<?php

namespace PlaygroundCms;

use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


        $options = $serviceManager->get('playgroundcore_module_options');
        $translator = $serviceManager->get('MvcTranslator');
        $locale = $options->getLocale();
        if (!empty($locale)) {
            //translator
            $translator->setLocale($locale);

            // plugins
            $translate = $serviceManager->get('ViewHelperManager')->get('translate');
            $translate->getTranslator()->setLocale($locale);
        }

        AbstractValidator::setDefaultTranslator($translator, 'playgroundcms');
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
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
                    $mapper->setEventManager($sm->get('SharedEventManager'));

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
                    $mapper->setEventManager($sm->get('SharedEventManager'));

                    return $mapper;
                },

                'playgroundcms_dynablock_mapper' => function ($sm) {
                    $mapper = new Mapper\Dynablock(
                        $sm->get('playgroundcms_doctrine_em'),
                        $sm->get('playgroundcms_module_options')
                    );
                    $mapper->setEventManager($sm->get('SharedEventManager'));

                    return $mapper;
                },

                'playgroundcms_page_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\Page(null, $sm, $translator);
                    $page = new Entity\Page();
                    $form->setInputFilter($page->getInputFilter());

                    return $form;
                },

                'playgroundcms_block_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\Block(null, $translator);

                    return $form;
                },

                'playgroundcms_dynablock_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
                    $form = new Form\Admin\Dynablock(null, $translator);

                    return $form;
                },

                'playgroundcms_slideshow_form' => function ($sm) {
                    $translator = $sm->get('MvcTranslator');
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
                    $translator = $sm->get('MvcTranslator');
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
                'blockWidget' => function ($sm) {
                    $blockMapper = $sm->get('playgroundcms_block_mapper');
                    
                    return new \PlaygroundCms\View\Helper\Block($blockMapper);
                },
                'dynablockWidget' => function ($sm) {
                    $dynablockMapper = $sm->get('playgroundcms_dynablock_mapper');

                    return new \PlaygroundCms\View\Helper\Dynablock($dynablockMapper);
                },
                // This admin navigation layer gives the authentication layer based on BjyAuthorize ;)
                'adminMenu' => function ($sm) {

                    $helperPluginManager = $sm->get('ViewHelperManager');
                    $nav = $helperPluginManager->get('navigation')->menu('admin_navigation');
                    $acl = $sm->get('BjyAuthorize\Service\Authorize')->getAcl();
                    $role = $sm->get('BjyAuthorize\Service\Authorize')->getIdentity();
                    $nav->setAcl($acl)
                    ->setRole($role)
                    ->setUseAcl()
                    ->setUlClass('nav')
                    ->setMaxDepth(10)
                    ->setRenderInvisible(false);

                    return $nav;
                },
                'slideshowWidget' => function ($sm) {
                    $slideshowService = $sm->get('playgroundcms_slideshow_service');
                    
                    return new \PlaygroundCms\View\Helper\Slideshow($slideshowService);
                }
            ),
        );
    }
}
