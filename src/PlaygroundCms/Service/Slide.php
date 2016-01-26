<?php

namespace PlaygroundCms\Service;

use PlaygroundCms\Entity\Slide as SlideEntity;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\NotEmpty;
use ZfcBase\EventManager\EventProvider;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use Zend\Stdlib\ErrorHandler;

class Slide extends EventProvider implements ServiceManagerAwareInterface
{

    const SLIDE_INACTIVE = 0;
    const SLIDE_ACTIVE = 1;

    public static $statuses = array(
        self::SLIDE_INACTIVE => 'inactive',
        self::SLIDE_ACTIVE => 'active');

    /**
     * @var slideMapper
     */
    protected $slideMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * drive path to game media files
     */
    protected $media_path = 'public/media/slide';

    /**
     * url path to game media files
     */
    protected $media_url = 'media/slide';

    protected $config = array();
    /**
     *
     * This service is ready for create a slide
     *
     * @param  array  $data
     * @param  string $formClass
     *
     * @return \PlaygroundCms\Entity\Slide
     */
    public function create(array $data)
    {
        $slide = new slideEntity();
        $slideshow = $this->getServiceManager()->get('playgroundcms_slideshow_mapper')->findById($data['slideshowId']);

        $form = $this->getServiceManager()->get('playgroundcms_slide_form');
        $form->bind($slide);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        $slide->setSlideshow($slideshow);
        $slide = $this->getSlideMapper()->insert($slide);
        $this->uploadMedia($slide, $data);
        $slide = $this->getSlideMapper()->update($slide);

        return $slide;
    }

    /**
     *
     * This service is ready for edit a slide
     *
     * @param  array  $data
     * @param  string $slide
     *
     * @return \PlaygroundCms\Entity\Slide
     */
    public function edit(array $data, $slide)
    {
        $form  = $this->getServiceManager()->get('playgroundcms_slide_form');
        $form->bind($slide);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        $slide = $form->getData();

        $this->uploadMedia($slide, $data);

        $slide = $this->getSlideMapper()->update($slide);

        return $slide;
    }

    public function getStatuses()
    {
        return self::$statuses;
    }

    public function uploadMedia($slide, $data)
    {
        if (!empty($data['uploadFile']['tmp_name'])) {
            $path = $this->getMediaPath() . '/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $media_url = $this->getMediaUrl() . '/';
            move_uploaded_file(
                $data['uploadFile']['tmp_name'],
                $path . "/" . $slide->getId() . "-" . $data['uploadFile']['name']
            );
            $file = $media_url . $slide->getId() . "-" . $data['uploadFile']['name'];
            $slide->setMedia($file);

        }
        return $slide;
    }
    
    /**
     * getSlideMapper
     *
     * @return SlideMapper
     */
    public function getSlideMapper()
    {
        if (null === $this->slideMapper) {
            $this->slideMapper = $this->getServiceManager()->get('playgroundcms_slide_mapper');
        }

        return $this->slideMapper;
    }

    /**
     * setSlideMapper
     * @param  SlideMapper $slideMapper
     *
     * @return PlaygroundCms\Entity\Slide Slide
     */
    public function setSlideMapper($slideMapper)
    {
        $this->slideMapper = $slideMapper;

        return $this;
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
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    public function setMediaPath($media_path)
    {
        $this->media_path = $media_path;

        return $this;
    }

    /**
     * @return string
     */
    public function getMediaPath()
    {
        return $this->media_path;
    }

    public function setMediaUrl($media_url)
    {
        $this->media_url = $media_url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->media_url;
    }
}
