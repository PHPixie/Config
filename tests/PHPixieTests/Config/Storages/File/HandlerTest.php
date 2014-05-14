<?php

namespace PHPixieTests\Config\Storages\File;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\File\Handler
 */
abstract class HandlerTest extends \PHPixieTests\AbstractConfigTest
{
    protected $handler;
    protected $file;

    public function setUp()
    {
        $this->removeFile();
        $this->file = sys_get_temp_dir().'/phpixie_config_handler';
        $this->handler = $this->handler();
    }

    public function tearDown()
    {
        $this->removeFile();
    }

    protected function removeFile()
    {
        if(file_exists($this->file))
            unlink($this->file);
    }

    abstract protected function handler();
}
