<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Service\Slide;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class SlideFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Slide($container);

        return $service;
    }
}
