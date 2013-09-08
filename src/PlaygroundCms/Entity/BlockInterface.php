<?php

namespace PlaygroundCms\Entity;

interface BlockInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $blockId
     * @return mixed
     */
    public function setId($blockId);

    /**
     * @param $identifier
     * @return mixed
     */
    public function setIdentifier($identifier);

    /**
     * @return mixed
     */
    public function getIdentifier();

    /**
     * @param $content
     * @return mixed
     */
    public function setContent($content);

    /**
     * @return mixed
     */
    public function getContent();
}
