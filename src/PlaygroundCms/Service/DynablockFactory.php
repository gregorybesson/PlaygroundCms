<?php
namespace PlaygroundCms\Service;

use PlaygroundCms\Service\Dynablock;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DynablockFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Service\Dynablock
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Dynablock($locator);

        return $service;
    }
}
