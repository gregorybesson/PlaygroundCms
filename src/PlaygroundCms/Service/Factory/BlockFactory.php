<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Service\Block;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlockFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Service\Block
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Block($locator);

        return $service;
    }
}
