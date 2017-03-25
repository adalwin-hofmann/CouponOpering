<?php namespace SOE\Services\FileStore;

interface FileStoreInterface
{
    public function store($file, $subpath = '');
}