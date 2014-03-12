<?php

namespace PHPixie\Config\Loader\File;

abstract Format {
    
    public abstract function read($file);
    public abstract function write($file, $data);
}