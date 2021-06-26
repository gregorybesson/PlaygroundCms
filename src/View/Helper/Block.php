<?php

namespace PlaygroundCms\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use PlaygroundCms\Mapper\Block as BlockMapper;

class Block extends AbstractHelper
{
    protected $blockMapper;

    public function __construct(BlockMapper $blockMapper)
    {
        $this->blockMapper = $blockMapper;
    }
    /**
     * @param  int|string $identifier
     * @return string
     */
    public function __invoke($identifier)
    {
        $block = $this->blockMapper->findByIdentifier($identifier);
        if ($block) {
            return $block->getContent();
        }

        return '';
    }
}
