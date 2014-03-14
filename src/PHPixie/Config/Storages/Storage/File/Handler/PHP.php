<?php

namespace PHPixie\Config\Storages\Storage\File\Handler;

class PHP implements \PHPixie\Config\Storages\Storage\File\Handler {
    
    public function read($file)
    {
        return require($file);
    }
    
    public function write($file, $data)
    {
        file_put_contents($file, "<?php\r\nreturn ".var_export($data, true).";");
    }
}