<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\DynablockController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminDynablockControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Controller\Admin\DynablockController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new DynablockController($locator);

        return $controller;
    }
}
