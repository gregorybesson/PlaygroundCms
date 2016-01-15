<?php

namespace PlaygroundCms\Service;

use PlaygroundCms\Entity\Slideshow as SlideshowEntity;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\NotEmpty;
use ZfcBase\EventManager\EventProvider;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use Zend\Stdlib\ErrorHandler;

class Slideshow extends EventProvider implements ServiceManagerAwareInterface
{

    const SLIDESHOW_INACTIVE = 0;
    const SLIDESHOW_ACTIVE = 1;

    public static $statuses = array(
        self::SLIDESHOW_INACTIVE => 'inactive',
        self::SLIDESHOW_ACTIVE => 'active');

    /**
     * @var slideshowMapper
     */
    protected $slideshowMapper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * drive path to game media files
     */
    protected $media_path = 'public/media/slideshow';

    /**
     * url path to game media files
     */
    protected $media_url = 'media/slideshow';

    protected $config = array();
    /**
     *
     * This service is ready for create a slideshow
     *
     * @param  array  $data
     * @param  string $formClass
     *
     * @return \PlaygroundCms\Entity\Slideshow
     */
    public function create(array $data)
    {
        $slideshow = new slideshowEntity();

        $form = $this->getServiceManager()->get('playgroundcms_slideshow_form');
        $form->bind($slideshow);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        $slideshow = $this->getSlideshowMapper()->insert($slideshow);
        $this->uploadMedia($slideshow, $data);
        $slideshow = $this->getSlideshowMapper()->update($slideshow);

        return $slideshow;
    }

    /**
     *
     * This service is ready for edit a slideshow
     *
     * @param  array  $data
     * @param  string $slideshow
     *
     * @return \PlaygroundCms\Entity\Slideshow
     */
    public function edit(array $data, $slideshow)
    {
        $form  = $this->getServiceManager()->get('playgroundcms_slideshow_form');

        $form->bind($slideshow);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        $slideshow = $form->getData();
        
        $this->uploadMedia($slideshow, $data);
        $slideshow = $this->getSlideshowMapper()->update($slideshow);
        
        return $slideshow;
    }

    public function getSlideshows()
    {
        return $this->getSlideshowMapper()->getSlideshows();
    }

    public function getStatuses()
    {
        return self::$statuses;
    }

    public function removeSlide($slideshowId, $slideToRemove)
    {
        $slideshow = $this->getSlideshowMapper()->findById($slideshowId);

        $slidesToKeep = array();

        foreach ($slideshow->getSlides() as $slide) {
            if ($slide->getId() != $slideToRemove->getId()) {
                $slidesToKeep[] = $slide;
            }
        }

        $slideshow->removeSlides();
        $slideshow = $this->getSlideshowMapper()->update($slideshow);

        foreach ($slidesToKeep as $slide) {
            $slideshow->addSlide($slide);
        }

        $slideshow = $this->getSlideshowMapper()->update($slideshow);
    }

    public function uploadMedia($slideshow, $data)
    {
        if (!empty($data['uploadFile']['tmp_name'])) {
            $path = $this->getMediaPath() . '/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $media_url = $this->getMediaUrl() . '/';
            move_uploaded_file($data['uploadFile']['tmp_name'], $path . "/" . $slideshow->getId() . "-" . $data['uploadFile']['name']);
            $file = $media_url . $slideshow->getId() . "-" . $data['uploadFile']['name'];
            if ($data['type'] == 1) {
                $slideshow->setMedia($file);
            } else {
                $result = $this->convertVideo($file);
                $slideshow->setMedia($result['thumb']);
                $slideshow->setVideos(json_encode($result['videos']));
            }
        }
        return $slideshow;
    }

    public function convertVideo($videoPath)
    {
        $binPath = $this->getConfigParam('binPath');
        $realVideoPath = 'public/'.$videoPath;
        $thumbnailPath = preg_replace("/\.[a-zA-Z0-9]{2,4}$/", '.png', $realVideoPath);
        $thumbDims = $this->getConfigParam('thumbnailWidth').':'.$this->getConfigParam('thumbnailHeight');

        $binCmd = $binPath . ' -i "' . $realVideoPath . '" -vf thumbnail,scale=' . $thumbDims . ' -vframes 1 -y "' . $thumbnailPath . '"';
        exec($binCmd);
        
        $newFormats = array(
            "mp4" => array("720p","480p","360p", "240p"),
            "ogg" => array("720p","480p","360p", "240p"),
            "webm" => array("720p","480p","360p", "240p"),
        );

        $videos = $this->newVideoFormat($binPath, $realVideoPath, $newFormats);

        if (file_exists($thumbnailPath)) {
            return array(
                'thumb'  => str_replace('public/', '', $thumbnailPath),
                'videos' => $videos,
            );
        } else {
            return false;
        }
    }

    public function newVideoFormat($binPath, $videoPath, $newFormats)
    {
        $qualityOptions = array(
            // array('resolution', 'videoBitrate', 'audioBitrate')
            "720p" => array('1280x720', '8000k', '384k'),
            "480p" => array('848x480', '5000k', '384k'),
            "360p" => array('640x368', '2500k', '128k'),
            "240p" => array('432x240', '1000k', '128k'),
        );
        $videos = array();
        foreach ($newFormats as $key => $format) {
            foreach ($format as $quality) {
                $newPath = preg_replace("/\.[a-zA-Z0-9]{2,4}$/", $quality.'.'.$key, $videoPath);
                exec($binPath . ' -i ' . $videoPath . ' -s ' . $qualityOptions[$quality][0] . ' -b ' . $qualityOptions[$quality][1] . ' -ac 2  -ab ' . $qualityOptions[$quality][2] . ' ' . $newPath);
                $videos[$key][$quality] = str_replace('public/', '', $newPath);
            }
        }

        return $videos;
    }
    
    /**
     * getSlideshowMapper
     *
     * @return SlideshowMapper
     */
    public function getSlideshowMapper()
    {
        if (null === $this->slideshowMapper) {
            $this->slideshowMapper = $this->getServiceManager()->get('playgroundcms_slideshow_mapper');
        }

        return $this->slideshowMapper;
    }

    /**
     * setSlideshowMapper
     * @param  SlideshowMapper $slideshowMapper
     *
     * @return PlaygroundCms\Entity\Slideshow Slideshow
     */
    public function setSlideshowMapper($slideshowMapper)
    {
        $this->slideshowMapper = $slideshowMapper;

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

    public function getConfigParam($configParam = '')
    {
        if (!$this->config) {
            $config = $this->getServiceManager()->get('config');
            if (isset($config['video_processing'])) {
                $this->config = $config['video_processing'];
            }
        }
        if ($configParam && array_key_exists($configParam, $this->config)) {
            return $this->config[$configParam];
        } else {
            return null;
        }
    }
}
