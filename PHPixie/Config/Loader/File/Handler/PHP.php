<?php

namespace PHPixie\Config\Loader\File\Format;

class PHP extends PHPixie\Config\Loader\File\Format {
    
    public function read($file)
    {
        return require($file);
    }
    
    public function write($file, $data)
    {
        file_put_contents($file, "<?php\r\nreturn ".var_export($data, true).";");
    }
}