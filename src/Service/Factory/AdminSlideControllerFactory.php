<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\SlideController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AdminSlideControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new SlideController($container);

        return $controller;
    }
}
