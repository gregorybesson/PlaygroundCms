<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\SlideController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminSlideControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Controller\Admin\SlideController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new SlideController($locator);

        return $controller;
    }
}
