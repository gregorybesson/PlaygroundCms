<?php

namespace PlaygroundCms\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use Zend\Stdlib\Hydrator\ClassMethods;
use PlaygroundCms\Mapper\DynablockInterface as DynablockMapperInterface;
use PlaygroundCms\Options\ModuleOptions;
use PlaygroundCms\Entity\Dynablock as EntityDynablock;

class Dynablock extends EventProvider implements ServiceManagerAwareInterface
{
    /**
     * @var DynablockMapperInterface
     */
    protected $dynablockMapper;

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

    public function create(array $data, EntityDynablock $dynablock)
    {
        $form  = $this->getServiceManager()->get('playgroundcms_dynablock_form');
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
        $form  = $this->getServiceManager()->get('playgroundcms_dynablock_form');
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
            $config = $this->getServiceManager()->get('Config');
            $dynareas = isset($config['dynacms']['dynareas']) ? $config['dynacms']['dynareas'] : null;
            $results = $this->getServiceManager()->get('application')->getEventManager()->trigger(
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
            $config = $this->getServiceManager()->get('Config');
            $dynablocks = isset($config['dynacms']['dynablocks']) ? $config['dynacms']['dynablocks'] : null;
            $blocks = $this->getAdminblockService()->getBlockMapper()->findBy(
                array('is_active' => 1, 'on_call' => 1),
                array('title' => 'ASC')
            );
            // foreach($blocks as $block){
            //     $dynablocks[$block->getIdentifier()] = array(
            //         'title'       => $block->getTitle(),
            //         'description' => 'bloc dynamique',
            //         'widget'      => 'PlaygroundNewsletter\Widget\Subscribe',
            //     ),
            // }
            $results = $this->getServiceManager()->get('application')->getEventManager()->trigger(
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
            $this->dynablockMapper = $this->getServiceManager()->get('PlaygroundCms_dynablock_mapper');
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
