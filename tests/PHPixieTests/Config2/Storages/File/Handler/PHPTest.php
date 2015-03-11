<?php

namespace PHPixieTests\Config\Storages\File\Handler;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\File\Handler\PHP
 */
class PHPTest extends \PHPixieTests\Config\Storages\File\HandlerTest
{
    protected function handler()
    {
        return new \PHPixie\Config\Storages\File\Handler\PHP;
    }

    /**
     * @covers ::write
     */
    public function testWrite()
    {
        $this->handler->write($this->file, array('test' => 5));
        $this->assertEquals(array('test' => 5), include($this->file));
    }

    /**
     * @covers ::read
     */
    public function testRead()
    {
        file_put_contents($this->file,  "<?php\r\nreturn ".var_export(array('test' => 5), true).";");
        $this->assertEquals(array('test' => 5), $this->handler->read($this->file));
    }
}
