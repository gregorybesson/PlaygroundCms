<?php

namespace PlaygroundCms\View\Helper;

use Zend\View\Helper\AbstractHelper;
use PlaygroundCms\Mapper\Block as BlockMapper;
use PlaygroundCms\Mapper\Dynablock as DynablockMapper;
use Zend\View\Model\ViewModel;

class Dynablock extends AbstractHelper
{
    protected $dynablockMapper;

    /**
     * @param  int|string $identifier
     * @return string
     */
    public function __invoke($identifier)
    {
        /**
            En fonction de identifier, je regarde en bdd ce que je dois afficher, puis j'affiche...
            Ca peut prendre cher mais je passerai par du cache
         */

        $result = '';
        $dynablocks = $this->getDynablockMapper()->findByDynarea($identifier);
        foreach ($dynablocks as $block) {
            if ($block->getType() == 'block') {
                $static = $this->getBlockMapper()->find((int) $block->getIdentifier());
                if ($static) {
                    $result[] = $static->getContent();
                }
            }
        }

        return $result;


        $vm = new ViewModel(array(
        ));
        $vm->setTemplate('playground-cms/index/grg.phtml');

        return $this->getView()->render($vm);
        return '';
    }

    /**
     * @param \PlaygroundCms\Mapper\Block $blockMapper
     */
    public function setBlockMapper(BlockMapper $blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlockMapper()
    {
        return $this->blockMapper;
    }

    /**
     * @param \PlaygroundCms\Mapper\Block $blockMapper
     */
    public function setDynablockMapper(DynablockMapper $dynablockMapper)
    {
        $this->dynablockMapper = $dynablockMapper;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDynablockMapper()
    {
        return $this->dynablockMapper;
    }
}
