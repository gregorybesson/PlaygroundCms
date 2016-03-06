<?php

namespace PlaygroundCms\Controller\Admin;

use PlaygroundCms\Service\Page as AdminPageService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageController extends AbstractActionController
{
    protected $options;
    protected $pageMapper;

    /**
     * @var UserService
     */
    protected $adminPageService;

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
        $filter = $this->getEvent()->getRouteMatch()->getParam('filter');
        $pageMapper = $this->getPageMapper();
        $pages = $pageMapper->findAllBy(array('publicationDate' => $filter));

        if (is_array($pages)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($pages));
            $paginator->setItemCountPerPage(20);
            $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        } else {
            $paginator = $pages;
        }

        return new ViewModel(
            array(
                'pages' => $paginator,
                'filter' => $filter,
            )
        );
    }

    public function createAction()
    {
        $form = $this->getServiceLocator()->get('playgroundcms_page_form');
        $form->setAttribute('method', 'post');

        $titleForm = 'Create article';

        $viewModel = new ViewModel(
            array(
                'titleForm' => $titleForm,
            )
        );
        $viewModel->setTemplate('playground-cms/page/page');

        $page = false;
        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $page = $this->getAdminPageService()->create($page, $data);
        }

        if (!$page) {
            return $viewModel->setVariables(array('form' => $form));
        }

        $this->flashMessenger()->setNamespace('playgroundcms')->addMessage('La page a été créée');

        return $this->redirect()->toRoute('admin/playgroundcmsadmin/pages/list');
    }

    public function editAction()
    {
        $pageId = $this->getEvent()->getRouteMatch()->getParam('pageId');

        if (!$pageId) {
            return $this->redirect()->toRoute('admin/playgroundcmsadmin/pages/list');
        }

        $page = $this->getAdminPageService()->getPageMapper()->findById($pageId);

        $form = $this->getServiceLocator()->get('playgroundcms_page_form');
        $form->setAttribute('method', 'post');
        $form->bind($page);

        $titleForm = 'Edit article';

        $viewModel = new ViewModel(
            array(
                'titleForm' => $titleForm,
            )
        );
        $viewModel->setTemplate('playground-cms/page/page');

        if ($this->getRequest()->isPost()) {
            $data = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );
            $page = $this->getAdminPageService()->edit($page, $data);
            if ($page) {
                $this->flashMessenger()->setNamespace('playgroundcms')->addMessage('La page a été mise à jour');

                return $this->redirect()->toRoute('admin/playgroundcmsadmin/pages/list');
            }
        }

        return $viewModel->setVariables(array('form' => $form));
    }

    public function removeAction()
    {
        $pageId = $this->getEvent()->getRouteMatch()->getParam('pageId');

        if (!$pageId) {
            return $this->redirect()->toRoute('admin/playgroundcmsadmin/pages/list');
        }

        $page = $this->getAdminPageService()->getPageMapper()->findById($pageId);

        if ($page) {
            try {
                $this->getAdminPageService()->getPageMapper()->remove($page);
                $this->flashMessenger()->setNamespace('playgroundcms')->addMessage('The page has been deleted');
            } catch (\Doctrine\DBAL\DBALException $e) {
            }
        }

        return $this->redirect()->toRoute('admin/playgroundcmsadmin/pages/list');
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

    public function getPageMapper()
    {
        if (null === $this->pageMapper) {
            $this->pageMapper = $this->getServiceLocator()->get('playgroundcms_page_mapper');
        }

        return $this->pageMapper;
    }

    public function setPageMapper(PageMapperInterface $pageMapper)
    {
        $this->pageMapper = $pageMapper;

        return $this;
    }

    public function getAdminPageService()
    {
        if (!$this->adminPageService) {
            $this->adminPageService = $this->getServiceLocator()->get('playgroundcms_page_service');
        }

        return $this->adminPageService;
    }

    public function setAdminPageService(AdminPageService $service)
    {
        $this->adminPageService = $service;

        return $this;
    }
}
