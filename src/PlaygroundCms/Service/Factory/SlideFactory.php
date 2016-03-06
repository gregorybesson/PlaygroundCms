<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Service\Slide;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SlideFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Service\Slide
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Slide($locator);

        return $service;
    }
}
