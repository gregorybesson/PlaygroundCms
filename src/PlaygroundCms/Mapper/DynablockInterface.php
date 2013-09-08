<?php

namespace PlaygroundCms\Mapper;

interface DynablockInterface
{
    public function findById($id);

    public function findByIdentifier($identifier);

    public function insert($block);

    public function update($block);
}
