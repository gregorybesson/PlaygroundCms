<?php

namespace PlaygroundCms\Mapper;

interface PageInterface
{
    public function findById($id);

    public function findByIdentifier($identifier);

    public function insert($page);

    public function update($page);
}
