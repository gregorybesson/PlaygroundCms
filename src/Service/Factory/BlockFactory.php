<?php
namespace PlaygroundCms\Service\Factory;

use PlaygroundCms\Service\Block;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class BlockFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Block($container);

        return $service;
    }
}
