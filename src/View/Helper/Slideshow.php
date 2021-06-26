<?php

namespace PlaygroundCms\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class Slideshow extends AbstractHelper
{
    protected $slideshowService = null;

    public function __construct(\PlaygroundCms\Service\Slideshow $slideshowService)
    {
        $this->slideshowService = $slideshowService;
    }

    public function __invoke($id)
    {
        $slider = $this->slideshowService->getSlideshowMapper()->findOneBy(array('id' => $id));

        return $this->getView()->render('playground-cms/widget/slideshow/display', array('slideshow' => $slider));
    }
}
