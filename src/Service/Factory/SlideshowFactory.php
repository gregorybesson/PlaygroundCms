<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Service\Slideshow;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class SlideshowFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Slideshow($container);

        return $service;
    }
}
