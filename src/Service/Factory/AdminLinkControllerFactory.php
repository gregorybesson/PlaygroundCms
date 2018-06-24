<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Controller\Admin\LinkController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AdminLinkControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new LinkController($container);

        return $controller;
    }
}
