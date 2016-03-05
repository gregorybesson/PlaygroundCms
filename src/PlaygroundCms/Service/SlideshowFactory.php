<?php
namespace PlaygroundCms\Service;

use PlaygroundCms\Service\Slideshow;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SlideshowFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundCms\Service\Slideshow
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Slideshow($locator);

        return $service;
    }
}
