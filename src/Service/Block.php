<?php

namespace PlaygroundCms\Service;

use Laminas\ServiceManager\ServiceManager;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Hydrator\ClassMethods;
use PlaygroundCms\Mapper\BlockInterface as BlockMapperInterface;
use PlaygroundCms\Options\ModuleOptions;
use PlaygroundCms\Entity\Block as EntityBlock;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\EventManager\EventManager;

class Block
{
    use EventManagerAwareTrait;

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

    /**
     *
     * @var ServiceManager
     */
    protected $serviceLocator;

    protected $event;

    public function __construct(ServiceLocatorInterface $locator)
    {
        $this->serviceLocator = $locator;
    }

    public function getServiceManager()
    {
        return $this->serviceLocator;
    }

    public function create(array $data, EntityBlock $block)
    {
        $form  = $this->serviceLocator->get('playgroundcms_block_form');
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
        $form  = $this->serviceLocator->get('playgroundcms_block_form');
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
            $this->blockMapper = $this->serviceLocator->get('playgroundcms_block_mapper');
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
            $this->setOptions($this->serviceLocator->get('playgroundcms_module_options'));
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

    public function getEventManager()
    {
        if ($this->event === NULL) {
            $this->event = new EventManager(
                $this->serviceLocator->get('SharedEventManager'), [get_class($this)]
            );
        }
        return $this->event;
    }
}
