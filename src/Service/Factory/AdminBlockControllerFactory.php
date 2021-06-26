<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\BlockController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AdminBlockControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new BlockController($container);

        return $controller;
    }
}
