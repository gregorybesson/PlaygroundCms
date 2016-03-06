<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\LinkController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminLinkControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Controller\Admin\LinkController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new LinkController($locator);

        return $controller;
    }
}
