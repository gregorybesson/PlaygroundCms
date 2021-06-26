<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\PageController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AdminPageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new PageController($container);

        return $controller;
    }
}
