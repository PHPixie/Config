<?php

namespace PHPixie\Config\Formats\Format;

class JSON implements \PHPixie\Config\Formats\Format
{
    public function read($file)
    {
        $data = json_decode(file_get_contents($file), true);
        
        if($data === null) {
            throw new \PHPixie\Config\Exception("Invalid JSON in config file");
        }
        
        return $data;
    }

    public function write($file, $data)
    {
        file_put_contents($file, json_encode($data));
    }
}
