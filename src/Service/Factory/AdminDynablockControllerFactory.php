<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\DynablockController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AdminDynablockControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new DynablockController($container);

        return $controller;
    }
}
