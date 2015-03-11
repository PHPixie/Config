<?php

namespace PHPixie\Config\Formats\Format;

class PHP implements \PHPixie\Config\Formats\Format
{
    public function read($file)
    {
        return require($file);
    }

    public function write($file, $data)
    {
        file_put_contents($file, "<?php\r\nreturn ".var_export($data, true).";");
    }
}
