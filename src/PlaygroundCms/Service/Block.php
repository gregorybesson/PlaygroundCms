<?php

namespace PlaygroundCms\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use Zend\Stdlib\Hydrator\ClassMethods;
use PlaygroundCms\Mapper\BlockInterface as BlockMapperInterface;
use PlaygroundCms\Options\ModuleOptions;
use PlaygroundCms\Entity\Block as EntityBlock;

class Block extends EventProvider implements ServiceManagerAwareInterface
{
    /**
     * @var BlockMapperInterface
     */
    protected $blockMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var array
     */
    protected $dynareas;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

    public function create(array $data, EntityBlock $block)
    {
        $form  = $this->getServiceManager()->get('playgroundcms_block_form');
        $form->setHydrator(new ClassMethods());
        $form->bind($block);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $block = $form->getData();

        $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            array('block' => $block, 'form' => $form, 'data' => $data)
        );
        $this->getBlockMapper()->insert($block);
        $this->getEventManager()->trigger(
            __FUNCTION__.'.post',
            $this,
            array('block' => $block, 'form' => $form, 'data' => $data)
        );

        return $block;
    }

    public function edit(array $data, EntityBlock $block)
    {
        $form  = $this->getServiceManager()->get('playgroundcms_block_form');
        $form->setHydrator(new ClassMethods());
        $form->bind($block);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $block = $form->getData();

        $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            array('block' => $block, 'form' => $form, 'data' => $data)
        );
        $this->getBlockMapper()->update($block);
        $this->getEventManager()->trigger(
            __FUNCTION__.'.post',
            $this,
            array('block' => $block, 'form' => $form, 'data' => $data)
        );

        return $block;
    }

    /**
     * @return \PlaygroundCms\Mapper\BlockInterface
     */
    public function getBlockMapper()
    {
        if (null === $this->blockMapper) {
            $this->blockMapper = $this->getServiceManager()->get('PlaygroundCms_block_mapper');
        }

        return $this->blockMapper;
    }

    /**
     * @param  \PlaygroundCms\Mapper\BlockInterface $blockMapper
     * @return Block
     */
    public function setBlockMapper(BlockMapperInterface $blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        if (!$this->options) {
            $this->setOptions($this->getServiceManager()->get('PlaygroundCms_module_options'));
        }

        return $this->options;
    }

    /**
     * @param ModuleOptions $options
     */
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return Block
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
