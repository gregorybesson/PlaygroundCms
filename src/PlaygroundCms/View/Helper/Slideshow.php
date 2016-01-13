<?php

namespace PlaygroundCms\View\Helper;

use PlaygroundCms\Service\Slideshow;
use Zend\View\Helper\AbstractHelper;

class Slideshow extends AbstractHelper
{
    protected $slideshowService = null;

    public function __construct(Slideshow $slideshowService)
    {
        $this->slideshowService = $slideshowService;
    }

    public function __invoke($id)
    {
        $slider = $this->slideshowService->getSlideshowMapper()->findOneBy(array('id' => $id));

        return $this->getView()->render('playground-cms/widget/slideshow/display', array('slideshow' => $slider));
    }
}
