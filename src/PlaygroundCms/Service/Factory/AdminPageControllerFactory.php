<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\PageController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminPageControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Controller\Admin\PageController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new PageController($locator);

        return $controller;
    }
}
