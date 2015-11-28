<?php

namespace PlaygroundCms\Service;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCms\Options\ModuleOptions;
use PlaygroundCms\Mapper\PageInterface as PageMapperInterface;
use PlaygroundCms\Entity\Page as EntityPage;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use Zend\Stdlib\ErrorHandler;

class Page extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PageMapperInterface
     */
    protected $pageMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

    public function create($page = false, array $data)
    {
        $entityManager = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        $form  = $this->getServiceManager()->get('playgroundcms_page_form');
        $form->get('publicationDate')->setOptions(array('format' => 'Y-m-d'));
        $form->get('closeDate')->setOptions(array('format' => 'Y-m-d'));

        if (! $page) {
            $form->setInputFilter($form->getInputFilter());
            $page = new EntityPage();
        }

        $form->bind($page);

        $path = $this->getOptions()->getMediaPath() . DIRECTORY_SEPARATOR;
        $media_url = $this->getOptions()->getMediaUrl() . '/';
        
        $identifierInput = $form->getInputFilter()->get('identifier');
        $noObjectExistsValidator = new NoObjectExistsValidator(array(
            'object_repository' => $entityManager->getRepository('PlaygroundCms\Entity\Page'),
            'fields'            => 'identifier',
            'messages'          => array('objectFound' => 'This url already exists !')
        ));
        
        $identifierInput->getValidatorChain()->addValidator($noObjectExistsValidator);

        if (isset($data['publicationDate']) && $data['publicationDate']) {
            $tmpDate = \DateTime::createFromFormat('d/m/Y', $data['publicationDate']);
            $data['publicationDate'] = $tmpDate->format('Y-m-d');
        }

        if (isset($data['closeDate']) && $data['closeDate']) {
            $tmpDate = \DateTime::createFromFormat('d/m/Y', $data['closeDate']);
            $data['closeDate'] = $tmpDate->format('Y-m-d');
        }

        $form->setData($data);
        
        if (!$form->isValid()) {
            if (isset($data['publicationDate']) && $data['publicationDate']) {
                $tmpDate = \DateTime::createFromFormat('Y-m-d', $data['publicationDate']);
                $data['publicationDate'] = $tmpDate->format('d/m/Y');
                $form->setData(array('publicationDate' => $data['publicationDate']));
            }
            if (isset($data['closeDate']) && $data['closeDate']) {
                $tmpDate = \DateTime::createFromFormat('Y-m-d', $data['closeDate']);
                $data['closeDate'] = $tmpDate->format('d/m/Y');
                $form->setData(array('closeDate' => $data['closeDate']));
            }
            return false;
        }

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('page' => $page, 'form' => $form, 'data' => $data));
        $this->getPageMapper()->insert($page);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('page' => $page, 'form' => $form, 'data' => $data));

        if (!empty($data['uploadMainImage']['tmp_name'])) {
            ErrorHandler::start();
            move_uploaded_file($data['uploadMainImage']['tmp_name'], $path . $page->getId() . "-" . $data['uploadMainImage']['name']);
            $page->setMainImage($media_url . $page->getId() . "-" . $data['uploadMainImage']['name']);
            ErrorHandler::stop(true);
        }

        if (!empty($data['uploadSecondImage']['tmp_name'])) {
            ErrorHandler::start();
            move_uploaded_file($data['uploadSecondImage']['tmp_name'], $path . $page->getId() . "-" . $data['uploadSecondImage']['name']);
            $page->setSecondImage($media_url . $page->getId() . "-" . $data['uploadSecondImage']['name']);
            ErrorHandler::stop(true);
        }
        $page = $this->getPageMapper()->update($page);

        return $page;
    }

    public function edit($page, array $data)
    {
        $entityManager = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        $form  = $this->getServiceManager()->get('playgroundcms_page_form');
        $form->get('publicationDate')->setOptions(array('format' => 'Y-m-d'));
        $form->get('closeDate')->setOptions(array('format' => 'Y-m-d'));
        $form->bind($page);

        $path = $this->getOptions()->getMediaPath() . DIRECTORY_SEPARATOR;
        $media_url = $this->getOptions()->getMediaUrl() . '/';
        
        $identifierInput = $form->getInputFilter()->get('identifier');
        $noObjectExistsValidator = new NoObjectExistsValidator(array(
            'object_repository' => $entityManager->getRepository('PlaygroundCms\Entity\Page'),
            'fields'            => 'identifier',
            'messages'          => array('objectFound' => 'This url already exists !')
        ));
        
        if ($page->getIdentifier() != $data['identifier']) {
            $identifierInput->getValidatorChain()->addValidator($noObjectExistsValidator);
        }

        if (isset($data['publicationDate']) && $data['publicationDate']) {
            $tmpDate = \DateTime::createFromFormat('d/m/Y', $data['publicationDate']);
            $data['publicationDate'] = $tmpDate->format('Y-m-d');
        }

        if (isset($data['closeDate']) && $data['closeDate']) {
            $tmpDate = \DateTime::createFromFormat('d/m/Y', $data['closeDate']);
            $data['closeDate'] = $tmpDate->format('Y-m-d');
        }

        $form->setData($data);
        
        if (!$form->isValid()) {
            if (isset($data['publicationDate']) && $data['publicationDate']) {
                $tmpDate = \DateTime::createFromFormat('Y-m-d', $data['publicationDate']);
                $data['publicationDate'] = $tmpDate->format('d/m/Y');
                $form->setData(array('publicationDate' => $data['publicationDate']));
            }
            if (isset($data['closeDate']) && $data['closeDate']) {
                $tmpDate = \DateTime::createFromFormat('Y-m-d', $data['closeDate']);
                $data['closeDate'] = $tmpDate->format('d/m/Y');
                $form->setData(array('closeDate' => $data['closeDate']));
            }
            return false;
        }

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('page' => $page, 'form' => $form, 'data' => $data));
        $this->getPageMapper()->insert($page);
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('page' => $page, 'form' => $form, 'data' => $data));

        if (!empty($data['uploadMainImage']['tmp_name'])) {
            ErrorHandler::start();
            move_uploaded_file($data['uploadMainImage']['tmp_name'], $path . $page->getId() . "-" . $data['uploadMainImage']['name']);
            $page->setMainImage($media_url . $page->getId() . "-" . $data['uploadMainImage']['name']);
            ErrorHandler::stop(true);
        }

        if (!empty($data['uploadSecondImage']['tmp_name'])) {
            ErrorHandler::start();
            move_uploaded_file($data['uploadSecondImage']['tmp_name'], $path . $page->getId() . "-" . $data['uploadSecondImage']['name']);
            $page->setSecondImage($media_url . $page->getId() . "-" . $data['uploadSecondImage']['name']);
            ErrorHandler::stop(true);
        }
        $page = $this->getPageMapper()->update($page);

        return $page;
    }

    /**
     * getActivePages
     *
     * @return Array of Page\Entity\Page
     */
    public function getActivePages($displayHome = true, $category = 0)
    {
        $em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        $today = new \DateTime("now");
        //$today->format('Y-m-d H:i:s');
        $today = $today->format('Y-m-d') . ' 23:59:59';

        $displayHomeClause='';
        if ($displayHome) {
            $displayHomeClause = " AND p.displayHome = true";
        }
        
        $categoryClause = " AND p.category = " . $category;

        // Page active with a startDate before today (or without startDate) and closeDate after today (or without closeDate)
        $query = $em->createQuery(
            'SELECT p FROM PlaygroundCms\Entity\Page p
                WHERE (p.publicationDate <= :date OR p.publicationDate IS NULL)
                AND (p.closeDate >= :date OR p.closeDate IS NULL)
                AND p.active = 1'
            . $displayHomeClause
            . $categoryClause
            .' ORDER BY p.publicationDate DESC'
        );
        $query->setParameter('date', $today);
        $pages = $query->getResult();

        // Je les classe par date de publication (date comme clé dans le tableau afin de pouvoir merger les objets
        // de type article avec le même procédé en les classant naturellement par date asc ou desc
        $arrayPages = array();
        foreach ($pages as $page) {
            if ($page->getPublicationDate()) {
                $key = $page->getPublicationDate()->format('Ymd').$page->getUpdatedAt()->format('Ymd').'-'.$page->getId();
            } else {
                $key = $page->getUpdatedAt()->format('Ymd') . $page->getUpdatedAt()->format('Ymd').'-'.$page->getId();
            }
            $arrayPages[$key] = $page;
        }

        return $arrayPages;
    }

    /**
     * getActiveSliderGames
     *
     * @return Array of Page\Entity\Game
     */
    public function getActiveSliderPages()
    {
        $em = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        $today = new \DateTime("now");
        //$today->format('Y-m-d H:i:s');
        $today = $today->format('Y-m-d') . ' 23:59:59';

        // Page active with a startDate before today (or without startDate) and closeDate after today (or without closeDate)
        $query = $em->createQuery(
            'SELECT p FROM PlaygroundCms\Entity\Page p
                WHERE (p.publicationDate <= :date OR p.publicationDate IS NULL)
                AND (p.closeDate >= :date OR p.closeDate IS NULL)
                AND p.active = 1 AND p.pushHome = true
                ORDER BY p.publicationDate DESC'
        );
        $query->setParameter('date', $today);
        $pages = $query->getResult();

        // Je les classe par date de publication (date comme clé dans le tableau afin de pouvoir merger les objets
        // de type article avec le même procédé en les classant naturellement par date asc ou desc
        $arrayPages = array();
        foreach ($pages as $page) {
            if ($page->getPublicationDate()) {
                $key = $page->getPublicationDate()->format('Ymd').$page->getUpdatedAt()->format('Ymd').'-'.$page->getId();
            } else {
                $key = $page->getUpdatedAt()->format('Ymd') . $page->getUpdatedAt()->format('Ymd').'-'.$page->getId();
            }

            $arrayPages[$key] = $page;
        }

        return $arrayPages;
    }

    /**
     * getPageMapper
     *
     * @return PageMapperInterface
     */
    public function getPageMapper()
    {
        if (null === $this->pageMapper) {
            $this->pageMapper = $this->getServiceManager()->get('playgroundcms_page_mapper');
        }

        return $this->pageMapper;
    }

    /**
     * setPageMapper
     *
     * @param  PageMapperInterface $pageMapper
     * @return User
     */
    public function setPageMapper(PageMapperInterface $pageMapper)
    {
        $this->pageMapper = $pageMapper;

        return $this;
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceManager()->get('playgroundcms_module_options'));
        }

        return $this->options;
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
     * @return Game
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
