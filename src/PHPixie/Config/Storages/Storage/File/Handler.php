<?php

namespace PHPixie\Config\Storages\Storage\File;

interface Handler {
    
    public function read($file);
    public function write($file, $data);
}