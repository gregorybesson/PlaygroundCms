<?php
namespace PlaygroundCms\Service;

use PlaygroundCms\Service\Page;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Service\Page
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Page($locator);

        return $service;
    }
}
