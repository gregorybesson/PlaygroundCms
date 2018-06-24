<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Service\Dynablock;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class DynablockFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Dynablock($container);

        return $service;
    }
}
