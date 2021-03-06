<?php

namespace PlaygroundCms\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SlideshowController extends AbstractActionController
{

 
    protected $slideshowService;
    protected $slideService;
    protected $slideshowForm;

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
        $slideshows = $this->getSlideshowService()->getSlideshows();

        $viewModel = new ViewModel();
        return $viewModel->setVariables(
            array(
                'flashMessages'   => $this->flashMessenger()->getMessages(),
                'slideshows'      => $slideshows,
            )
        );
    }

    public function createAction()
    {
        $form = $this->getSlideshowForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = array_merge(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $slideshow = $this->getSlideshowService()->create($data);
            if ($slideshow) {
                $this->flashMessenger()->addMessage(' alert-success');
                $this->flashMessenger()->addMessage('The slideshow "'.$slideshow->getTitle().'" was created');

                if ($data['submit'] === 'slide') {
                    return $this->redirect()->toUrl(
                        $this->adminUrl()->fromRoute(
                            'playgroundcmsadmin/slide',
                            array('slideshowId' => $slideshow->getId())
                        )
                    );
                } else {
                    return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/slideshow'));
                }
            } else {
                $state = 'alert-danger';
                $message = 'The slideshow was not created!';
            }
        }

        $viewModel = new ViewModel();

        return $viewModel->setVariables(array(
            'form'          => $form,
            'flashMessages' => $this->flashMessenger()->getMessages()
        ));
    }

    public function editAction()
    {
        $slideshowId = $this->getEvent()->getRouteMatch()->getParam('slideshowId');
        $slideshow = $this->getSlideshowService()->getSlideshowMapper()->findById($slideshowId);
        $form = $this->getSlideshowForm();
        $request = $this->getRequest();

        $form->bind($slideshow);

        if ($request->isPost()) {
            $data = array_merge(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $slideshow = $this->getSlideshowService()->edit($data, $slideshow);

            if ($slideshow) {
                $this->flashMessenger()->addMessage(' alert-success');
                $this->flashMessenger()->addMessage('The slideshow "'.$slideshow->getTitle().'" was edited');

                return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/slideshow'));
            }
        }

        $viewModel = new ViewModel();

        $slides = $this->getSlideService()->getSlideMapper()->findBy(['slideshow' => $slideshow], ['position' => 'ASC']);

        return $viewModel->setVariables(
            array(
                'form'          => $form,
                'slideshowId'   => $slideshowId,
                'flashMessages' => $this->flashMessenger()->getMessages(),
                'slides'        => $slides,
            )
        );
    }
   
    public function removeAction()
    {
        $slideshowId = $this->getEvent()->getRouteMatch()->getParam('slideshowId');
        $slideshow = $this->getSlideshowService()->getSlideshowMapper()->findById($slideshowId);

        $title = $slideshow->getTitle();
        $this->getSlideshowService()->getSlideshowMapper()->remove($slideshow);
        
        $this->flashMessenger()->addMessage(' alert-success');
        $this->flashMessenger()->addMessage('The slideshow "'.$title.'" was deleted');
        

        return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/slideshow'));
    }

    public function activateAction()
    {
        $currentDate = new \DateTime('NOW');
        $slideshowId = $this->getEvent()->getRouteMatch()->getParam('slideshowId');
        $slideshow = $this->getSlideshowService()->getSlideshowMapper()->findById($slideshowId);
        $slideshow->setActive(true);
        $slideshow->setUpdatedAt($currentDate->format('Y-m-d'));
        $slideshow = $this->getSlideshowService()->getSlideshowMapper()->update($slideshow);
        
        if ($slideshow) {
            $this->flashMessenger()->addMessage(' alert-success');
            $this->flashMessenger()->addMessage('The slideshow "'.$slideshow->getTitle().'" was activated');
        } else {
            $this->flashMessenger()->addMessage(' alert-danger');
            $this->flashMessenger()->addMessage('The slideshow "'.$slideshow->getTitle().'" was not activated');
        }

        return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/slideshow'));
    }

    public function getSlideshowForm()
    {
        if ($this->slideshowForm === null) {
            $this->slideshowForm = $this->getServiceLocator()->get('playgroundcms_slideshow_form');
        }
        return $this->slideshowForm;
    }

    public function getSlideshowService()
    {
        if (null === $this->slideshowService) {
            $this->slideshowService = $this->getServiceLocator()->get('playgroundcms_slideshow_service');
        }

        return $this->slideshowService;
    }

    public function setSlideshowService($slideshowService)
    {
        $this->slideshowService = $slideshowService;

        return $this;
    }

    public function setSlideService($slideService)
    {
        $this->slideService = $slideService;

        return $this;
    }

    public function getSlideService()
    {
        if (null === $this->slideService) {
            $this->slideService = $this->getServiceLocator()->get('playgroundcms_slide_service');
        }

        return $this->slideService;
    }
}
