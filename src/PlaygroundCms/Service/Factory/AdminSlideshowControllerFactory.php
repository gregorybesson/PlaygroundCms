<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\SlideshowController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminSlideshowControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Controller\Admin\SlideshowController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new SlideshowController($locator);

        return $controller;
    }
}
