<?php

namespace PlaygroundCms\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * drive path to game media files
     */
    protected $media_path = 'public/media/cms';

    /**
     * url path to game media files
     */
    protected $media_url = 'media/cms';

    /**
     * @var string
     */
    protected $dynablockEntityClass = 'PlaygroundCms\Entity\Dynablock';

    /**
     * @var string
     */
    protected $pageEntityClass = 'PlaygroundCms\Entity\Page';

    /**
     * @var string
     */
    protected $blockEntityClass = 'PlaygroundCms\Entity\Block';

    /**
     * Set page entity class name
     *
     * @param $pageEntityClass
     * @return ModuleOptions
     */
    public function setPageEntityClass($pageEntityClass)
    {
        $this->pageEntityClass = $pageEntityClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPageEntityClass()
    {
        return $this->pageEntityClass;
    }

    /**
     * Set block entity class name
     *
     * @param $blockEntityClass
     * @return ModuleOptions
     */
    public function setBlockEntityClass($blockEntityClass)
    {
        $this->blockEntityClass = $blockEntityClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlockEntityClass()
    {
        return $this->blockEntityClass;
    }

    public function getDynablockEntityClass()
    {
        return $this->dynablockEntityClass;
    }

    public function setDynablockEntityClass($dynablockEntityClass)
    {
        $this->dynablockEntityClass = $dynablockEntityClass;

        return $this;
    }

    /**
     * Set media path
     *
     * @param  string                          $media_path
     * @return \PlaygroundCms\Options\ModuleOptions
     */
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
        if (!is_dir($this->media_path)) {
            mkdir($this->media_path, 0777, true);
        }

        return $this->media_path;
    }

    /**
     *
     * @param  string                          $media_url
     * @return \PlaygroundCms\Options\ModuleOptions
     */
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
