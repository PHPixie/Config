<?php

namespace PHPixie\Config\Formats;

interface Format
{
    public function read($file);
    public function write($file, $data);
}
