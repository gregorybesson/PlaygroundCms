<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\SlideshowController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AdminSlideshowControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new SlideshowController($container);

        return $controller;
    }
}
