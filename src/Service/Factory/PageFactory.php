<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Service\Page;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class PageFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Page($container);

        return $service;
    }
}
