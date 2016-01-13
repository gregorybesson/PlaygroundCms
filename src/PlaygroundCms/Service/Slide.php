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
            if ($data['type'] == 1) {
                $slide->setMedia($file);
            } else {
                $result = $this->convertVideo($file);
                $slide->setMedia($result['thumb']);
                $slide->setVideos(json_encode($result['videos']));
            }
        }
        return $slide;
    }

    public function convertVideo($videoPath)
    {
        $binPath = $this->getConfigParam('binPath');
        $realVideoPath = 'public/'.$videoPath;
        $thumbnailPath = preg_replace("/\.[a-zA-Z0-9]{2,4}$/", '.png', $realVideoPath);
        $thumbDims = $this->getConfigParam('thumbnailWidth').':'.$this->getConfigParam('thumbnailHeight');

        $binCmd = $binPath . ' -i "' . $realVideoPath . '" -vf thumbnail,scale=';
        $binCmd .= $thumbDims . ' -vframes 1 -y "' . $thumbnailPath . '"';
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
