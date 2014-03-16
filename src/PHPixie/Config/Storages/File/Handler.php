<?php

namespace PHPixie\Config\Storages\File;

interface Handler
{
    public function read($file);
    public function write($file, $data);
}
