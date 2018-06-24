<?php

namespace PlaygroundCms\View\Helper;

use Zend\View\Helper\AbstractHelper;
use PlaygroundCms\Mapper\Block as BlockMapper;
use PlaygroundCms\Mapper\Dynablock as DynablockMapper;
use Zend\View\Model\ViewModel;

class Dynablock extends AbstractHelper
{
    protected $dynablockMapper;

    public function __construct(\PlaygroundCms\Mapper\Dynablock  $dynablockMapper)
    {
        $this->dynablockMapper = $dynablockMapper;
    }

    /**
     * @param  int|string $identifier
     * @return string
     */
    public function __invoke($identifier)
    {
        $result = '';
        $dynablocks = $this->dynablockMapper->findByDynarea($identifier);
        foreach ($dynablocks as $block) {
            $result .= $this->getView()->{$block->getType()}($block->getIdentifier());
        }
        
        return $result;
    }
}
