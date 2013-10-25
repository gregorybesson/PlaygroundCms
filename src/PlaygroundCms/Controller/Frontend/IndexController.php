<?php

namespace PlaygroundCms\Controller\Frontend;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $pageService;
    protected $options;

    public function indexAction()
    {
        $identifier = $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$identifier) {
            return $this->notFoundAction();
        }

        $sp = $this->getPageService();
        $page = $sp->getPageMapper()->findByIdentifier($identifier);

        if (!$page) {
            return $this->notFoundAction();
        }

        if (!$page->getActive()) {
            return $this->notFoundAction();
        }
        
         $this->layout()->setVariables(
            array(
                'breadcrumbTitle' => $page->getTitle(),
            )
        );
        
        $viewModel = new ViewModel(
            array('page' => $page)
        );

        return $viewModel;
    }

    public function winnerListAction()
    {
        $pages = $this->getPageService()->getActivePages(false);

        if (is_array($pages)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($pages));
            $paginator->setItemCountPerPage(7);
            $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));
        } else {
            $paginator = $pages;
        }

        //$adserving = $this->getOptions()->getAdServing();
        //$adserving['cat2'] = 'article';
        
        $this->layout()->setVariables(
            array(
                //'adserving'       => $adserving,
                'currentPage' => array(
                    'pageGames' => '',
                    'pageWinners' => 'winners'
                ),
            )
        );

        return new ViewModel(array( 'pages' => $paginator));
    }

    public function winnerPageAction()
    {
    	
		$nextkey 	 = 0;
        $previouskey = 0;
		
        $identifier = $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$identifier) {
            return $this->notFoundAction();
        }

        $mapper = $this->getServiceLocator()->get('playgroundcms_page_mapper');
        $pages = $this->getPageService()->getActivePages(false, 0);
        $page = $mapper->findByIdentifier($identifier);
		
		$arrayPages = array();

        foreach ($pages as $key=>$p) {
			$arrayPages[] = $p;
            foreach ($arrayPages as $key => $value) {
				if ($p->getIdentifier() == $identifier) {
					$nextkey = $key+1;
					$previouskey = $key-1;
				}
			}
        }
        if ($previouskey > -1) {
            $previousid = $arrayPages[$previouskey]->getIdentifier();
        } else {
            $previousid = null;
        }
        if (isset($arrayPages[$nextkey])) {
            $nextid = $arrayPages[$nextkey]->getIdentifier();
        } else {
            $nextid = null;
        }

        if (!$page || !$page->getActive()) {
            return $this->notFoundAction();
        }

        $bitlyclient = $this->getOptions()->getBitlyUrl();
        $bitlyuser = $this->getOptions()->getBitlyUsername();
        $bitlykey = $this->getOptions()->getBitlyApiKey();

        $this->getViewHelper('HeadMeta')->setProperty('bt:client', $bitlyclient);
        $this->getViewHelper('HeadMeta')->setProperty('bt:user', $bitlyuser);
        $this->getViewHelper('HeadMeta')->setProperty('bt:key', $bitlykey);

        /*$adserving = $this->getOptions()->getAdServing();
        $adserving['cat2'] = 'article';
        $adserving['cat3'] = '&EASTarticleid='.$page->getId();*/
        
        $this->layout()->setVariables(
            array(
                'breadcrumbTitle' => $page->getTitle(),
                //'adserving'       => $adserving,
                'currentPage' => array(
                    'pageGames' => '',
                    'pageWinners' => 'winners'
                ),
            )
        );

        $viewModel = new ViewModel(
            array(
                'page' => $page,
                'pagename' => $page->getTitle(),
                'nextid' => $nextid,
                'previousid' => $previousid,
                'bitlyclient'	=> $bitlyclient,
                'bitlyuser'		=> $bitlyuser,
                'bitlykey'		=> $bitlykey
                )
            );

        return $viewModel;
    }

    public function getPageService()
    {
        if (!$this->pageService) {
            $this->pageService = $this->getServiceLocator()->get('playgroundcms_page_service');
        }

        return $this->pageService;
    }

    public function setPageService(\PlaygroundCms\Service\Page $pageService)
    {
        $this->pageService = $pageService;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options) {
            $this->setOptions($this->getServiceLocator()->get('playgroundcore_module_options'));
        }

        return $this->options;
    }

         public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    protected function getViewHelper($helperName)
    {
        return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
    }
}
