<?php

namespace PlaygroundCms\Controller\Admin;

use PlaygroundCms\Service\Dynablock as AdminDynablockService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\ServiceManager\ServiceLocatorInterface;

class DynablockController extends AbstractActionController
{
    protected $options;
    protected $blockMapper;

    /**
     * @var adminBlockService
     */
    protected $adminDynablockService;

    protected $adminBlockService;

    /**
     *
     * @var ServiceManager
     */
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $locator)
    {
        $this->serviceLocator = $locator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function listAction()
    {
        $dynareas   = $this->getAdminDynablockService()->getDynareas();
        $activeDynablocks = $this->getAdminDynablockService()->getDynablockMapper()->findActiveDynablocks();
        
        //$dynablocks = $this->getAdminDynablockService()->getDynablocks();

        $activeDynareas = array();
        foreach ($activeDynablocks as $activeDynablock) {
            $activeDynareas[$activeDynablock->getDynarea()][] = $activeDynablock;
        }

        if (is_array($activeDynareas)) {
            $paginator = new \Laminas\Paginator\Paginator(new \Laminas\Paginator\Adapter\ArrayAdapter($activeDynareas));
            $paginator->setItemCountPerPage(10);
            $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        } else {
            $paginator = $activeDynareas;
        }

        return array(
            'activeDynareas' => $activeDynareas,
            'dynareas'   => $dynareas
        );
    }

    public function createAction()
    {
        $dynareaId = $this->getEvent()->getRouteMatch()->getParam('dynareaId');
        if (!$dynareaId) {
            return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/dynablocks/list'));
        }
        $dynablocksArea = $this->getAdminDynablockService()->getDynablockMapper()->findByDynarea($dynareaId);

        $dynablocks = $this->getAdminDynablockService()->getDynablocks();

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();

            $blockList = $data['blockList'];
            $dynarea   = $data['dynarea'];

            $result = $this->getAdminDynablockService()->updateDynarea($dynarea, $blockList);
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);

            return $viewModel;
        }
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-cms/dynablock/dynablock');

        return $viewModel->setVariables(array(
            'dynablocks'    => $dynablocksArea,
            'blocks'        => $dynablocks,
            'dynarea'       => $dynareaId
        ));
    }

    public function removeAction()
    {
        $dynareaId = $this->getEvent()->getRouteMatch()->getParam('dynareaId');
        if (!$dynareaId) {
            return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/dynablocks/list'));
        }
        $this->getAdminDynablockService()->getDynablockMapper()->clear($dynareaId);

        return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/dynablocks/list'));
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('playgroundcms_module_options'));
        }

        return $this->options;
    }

    public function getBlockMapper()
    {
        if (null === $this->blockMapper) {
            $this->blockMapper = $this->getServiceLocator()->get('playgroundcms_block_mapper');
        }

        return $this->blockMapper;
    }

    public function setBlockMapper(BlockMapperInterface $blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }

    public function getAdminDynablockService()
    {
        if (!$this->adminDynablockService) {
            $this->adminDynablockService = $this->getServiceLocator()->get('playgroundcms_dynablock_service');
        }

        return $this->adminDynablockService;
    }

    public function setAdminDynablockService(AdminDynablockService $service)
    {
        $this->adminDynablockService = $service;

        return $this;
    }

    public function getAdminBlockService()
    {
        if (!$this->adminBlockService) {
            $this->adminBlockService = $this->getServiceLocator()->get('playgroundcms_block_service');
        }

        return $this->adminBlockService;
    }

    public function setAdminBlockService(AdminBlockService $service)
    {
        $this->adminBlockService = $service;

        return $this;
    }
}
