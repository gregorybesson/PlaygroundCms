<?php

namespace PlaygroundCms\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Hydrator\ClassMethods;
use PlaygroundCms\Mapper\DynablockInterface as DynablockMapperInterface;
use PlaygroundCms\Options\ModuleOptions;
use PlaygroundCms\Entity\Dynablock as EntityDynablock;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;

class Dynablock
{
    use EventManagerAwareTrait;

    /**
     * @var DynablockMapperInterface
     */
    protected $dynablockMapper;

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
     * @var array
     */
    protected $dynablocks;

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

    public function create(array $data, EntityDynablock $dynablock)
    {
        $form  = $this->serviceLocator->get('playgroundcms_dynablock_form');
        //$form->setHydrator(new ClassMethods());
        $form->bind($dynablock);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $dynablock = $form->getData();

        $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            array('dynablock' => $dynablock, 'form' => $form, 'data' => $data)
        );
        $this->getDynablockMapper()->insert($dynablock);
        $this->getEventManager()->trigger(
            __FUNCTION__.'.post',
            $this,
            array('dynablock' => $dynablock, 'form' => $form, 'data' => $data)
        );

        return $dynablock;
    }

    public function updateDynarea($dynarea, $blockList)
    {
        $this->getDynablockMapper()->clear($dynarea);

        foreach ($blockList as $position => $dynablock) {
            $detail = explode('_', $dynablock);
            $dynablock = new EntityDynablock;
            $dynablock->setDynarea($dynarea)
                ->setIdentifier($detail[1])
                ->setType($detail[0])
                ->setIsActive(1)
                ->setPosition($position);
            $this->getDynablockMapper()->insert($dynablock);
        }

        return true;
    }

    public function edit(array $data, EntityDynablock $dynablock)
    {
        $form  = $this->serviceLocator->get('playgroundcms_dynablock_form');
        $form->setHydrator(new ClassMethods());
        $form->bind($dynablock);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        $dynablock = $form->getData();

        $this->getEventManager()->trigger(
            __FUNCTION__,
            $this,
            array('dynablock' => $dynablock, 'form' => $form, 'data' => $data)
        );
        $this->getDynablockMapper()->update($dynablock);
        $this->getEventManager()->trigger(
            __FUNCTION__.'.post',
            $this,
            array('dynablock' => $dynablock, 'form' => $form, 'data' => $data)
        );

        return $dynablock;
    }

    /**
     * @return multitype:
     */
    public function getDynareas()
    {
        if ($this->dynareas == null) {
            $config = $this->serviceLocator->get('Config');
            $dynareas = isset($config['dynacms']['dynareas']) ? $config['dynacms']['dynareas'] : null;
            $results = $this->serviceLocator->get('Application')->getEventManager()->trigger(
                __FUNCTION__,
                $this,
                array('dynareas' => $dynareas)
            )->last();

            if ($results) {
                $this->dynareas = $results;
            } else {
                $this->dynareas = $dynareas;
            }
        }

        return $this->dynareas;
    }

    /**
     * @return multitype:
     */
    public function getDynablocks()
    {
        if ($this->dynablocks == null) {
            $dynablocks = array();
            $config = $this->serviceLocator->get('Config');

            // Dynablocks described by config
            $dynablocksConfig = isset($config['dynacms']['dynablocks']) ? $config['dynacms']['dynablocks'] : null;
            if (is_array($dynablocksConfig)) {
                foreach ($dynablocksConfig as $block) {
                    $d = new \PlaygroundCms\Entity\Dynablock();
                    $d->setTitle($block['title']);
                    $d->setIdentifier($block['identifier']);
                    $d->setType($block['type']);
                    $dynablocks[] = $d;
                }
            }

            // CMS blocks which are "on_call" = dynablock compatible
            $blocks = $this->getBlockMapper()->findBy(
                array('is_active' => 1, 'on_call' => 1),
                array('title' => 'ASC')
            );

            foreach ($blocks as $block) {
                $d = new \PlaygroundCms\Entity\Dynablock();
                $d->setTitle($block->getTitle());
                $d->setIdentifier($block->getId());
                $d->setType('playgroundBlock');
                $dynablocks[] = $d;
            }

            // Dynablocks exposed via listener
            $results = $this->serviceLocator->get('Application')->getEventManager()->trigger(
                __FUNCTION__,
                $this,
                array('dynablocks' => $dynablocks)
            )->last();

            if ($results) {
                $this->dynablocks = $results;
            } else {
                $this->dynablocks = $dynablocks;
            }
        }

        return $this->dynablocks;
    }

    /**
     * @return \PlaygroundCms\Mapper\BlockInterface
     */
    public function getDynablockMapper()
    {
        if (null === $this->dynablockMapper) {
            $this->dynablockMapper = $this->serviceLocator->get('playgroundcms_dynablock_mapper');
        }

        return $this->dynablockMapper;
    }

    /**
     * @param  \PlaygroundCms\Mapper\BlockInterface $dynablockMapper
     * @return Block
     */
    public function setDynablockMapper(DynablockMapperInterface $dynablockMapper)
    {
        $this->dynablockMapper = $dynablockMapper;

        return $this;
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
            $this->setOptions($this->serviceLocator->get('PlaygroundCms_module_options'));
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
