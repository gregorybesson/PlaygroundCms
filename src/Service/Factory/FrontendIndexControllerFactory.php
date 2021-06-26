<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Frontend\IndexController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class FrontendIndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new IndexController($container);

        return $controller;
    }
}
