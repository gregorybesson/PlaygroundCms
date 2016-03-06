<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\BlockController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminBlockControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Controller\Admin\BlockController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new BlockController($locator);

        return $controller;
    }
}
