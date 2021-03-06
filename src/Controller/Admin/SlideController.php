<?php

namespace PlaygroundCms\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SlideController extends AbstractActionController
{
    protected $slideService;
    protected $slideshowService;
    protected $slideForm;

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

    public function createAction()
    {
        $slideshowId = $this->getEvent()->getRouteMatch()->getParam('slideshowId');
        $form = $this->getSlideForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = array_merge(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $slide = $this->getSlideService()->create($data);

            if ($slide) {
                $this->flashMessenger()->addMessage(' alert-success');
                $this->flashMessenger()->addMessage('The slide "'.$slide->getTitle().'" was created');

                return $this->redirect()->toUrl(
                    $this->adminUrl()->fromRoute(
                        'playgroundcmsadmin/slideshow/edit',
                        array('slideshowId' => $slideshowId)
                    )
                );
            } else {
                $state = 'alert-danger';
                $message = 'The slide was not created!';
            }
        }

        $viewModel = new ViewModel();

        return $viewModel->setVariables(
            array(
                'form'          => $form,
                'slideshowId'   => $slideshowId,
                'flashMessages' => $this->flashMessenger()->getMessages(),
            )
        );
    }

    public function editAction()
    {
        $slideId = $this->getEvent()->getRouteMatch()->getParam('id');
        $slideshowId = $this->getEvent()->getRouteMatch()->getParam('slideshowId');
        $slide = $this->getSlideService()->getSlideMapper()->findById($slideId);

        $form = $this->getSlideForm();

        $request = $this->getRequest();

        $form->bind($slide);

        if ($request->isPost()) {
            $data = array_merge(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $slide = $this->getSlideService()->edit($data, $slide);

            if ($slide) {
                $this->flashMessenger()->addMessage(' alert-success');
                $this->flashMessenger()->addMessage('The slide "'.$slide->getTitle().'" was edited');

                return $this->redirect()->toUrl(
                    $this->adminUrl()->fromRoute(
                        'playgroundcmsadmin/slideshow/edit',
                        array('slideshowId' => $slideshowId)
                    )
                );
            }
        }

        $viewModel = new ViewModel();

        return $viewModel->setVariables(array(
            'form'          => $form,
            'flashMessages' => $this->flashMessenger()->getMessages(),
            'slideshowId'   => $slideshowId,
        ));
    }
   
    public function removeAction()
    {
        $currentDate = new \DateTime('NOW');

        $slideId = $this->getEvent()->getRouteMatch()->getParam('id');
        $slideshowId = $this->getEvent()->getRouteMatch()->getParam('slideshowId');

        $slide = $this->getSlideService()->getSlideMapper()->findById($slideId);

        $this->getSlideService()->getSlideMapper()->remove($slide);
        
        $this->flashMessenger()->addMessage(' alert-success');
        $this->flashMessenger()->addMessage('The slide "'.$title.'" was deleted');

        return $this->redirect()->toUrl(
            $this->adminUrl()->fromRoute(
                'playgroundcmsadmin/slideshow/edit',
                array('slideshowId' => $slideshowId)
            )
        );
    }

    public function activateAction()
    {
        $currentDate = new \DateTime('NOW');
        $slideId = $this->getEvent()->getRouteMatch()->getParam('slideId');
        $slide = $this->getSlideService()->getSlideMapper()->findById($slideId);
        $slide->setActive(true);
        $slide->setUpdatedAt($currentDate->format('Y-m-d'));
        $slide = $this->getSlideService()->getSlideMapper()->update($slide);
        
        if ($slide) {
            $this->flashMessenger()->addMessage(' alert-success');
            $this->flashMessenger()->addMessage('The slide "'.$slide->getTitle().'" was activated');
        } else {
            $this->flashMessenger()->addMessage(' alert-danger');
            $this->flashMessenger()->addMessage('The slide "'.$slide->getTitle().'" was not activated');
        }

        return $this->redirect()->toUrl($this->adminUrl()->fromRoute('playgroundcmsadmin/slide'));
    }

    public function getSlideForm()
    {
        if ($this->slideForm === null) {
            $this->slideForm = $this->getServiceLocator()->get('playgroundcms_slide_form');
        }
        return $this->slideForm;
    }

    public function getSlideService()
    {
        if (null === $this->slideService) {
            $this->slideService = $this->getServiceLocator()->get('playgroundcms_slide_service');
        }

        return $this->slideService;
    }

    public function getSlideshowService()
    {
        if (null === $this->slideshowService) {
            $this->slideshowService = $this->getServiceLocator()->get('playgroundcms_slideshow_service');
        }

        return $this->slideshowService;
    }

    public function setSlideService($slideService)
    {
        $this->slideService = $slideService;

        return $this;
    }
}
