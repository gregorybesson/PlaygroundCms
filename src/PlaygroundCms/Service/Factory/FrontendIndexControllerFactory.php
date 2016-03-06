<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Frontend\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FrontendIndexControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Controller\Frontend\IndexController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new IndexController($locator);

        return $controller;
    }
}
