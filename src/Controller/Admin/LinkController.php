<?php

namespace PlaygroundCms\Controller\Admin;

//use PlaygroundCms\Service\Link as AdminLinkService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;

class LinkController extends AbstractActionController
{
    protected $options;
    protected $linkMapper;

    /**
     * @var UserService
     */
    protected $adminLinkService;

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
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-cms/links/list');
        return $viewModel;
    }

    public function createAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-cms/links/create');
        return $viewModel;
    }

    public function editAction()
    {
    }

    public function removeAction()
    {
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
}
