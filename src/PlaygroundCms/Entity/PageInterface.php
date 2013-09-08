<?php

namespace PlaygroundCms\Entity;

interface PageInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $pageId
     * @return mixed
     */
    public function setId($pageId);

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
     * @param $title
     * @return mixed
     */
    public function setTitle($title);

    /**
     * @return mixed
     */
    public function getTitle();

    /**
     * @param $content
     * @return mixed
     */
    public function setContent($content);

    /**
     * @return mixed
     */
    public function getContent();
	
	/**
     * @param $heading
     * @return mixed
     */
    public function setHeading($heading);

    /**
     * @return mixed
     */
    public function getHeading();
}
