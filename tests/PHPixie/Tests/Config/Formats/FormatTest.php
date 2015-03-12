<?php

namespace PHPixie\Tests\Config\Formats;

abstract class FormatTest extends \PHPixie\Test\Testcase
{
    protected $file;
    
    protected $handler;
    
    protected $data = array(
        'pixie' => array(
            'name'    => 'Trixie',
            'flowers' => array('Red', 'Green')
        )
    );

    public function setUp()
    {
        $this->removeFile();
        $this->file = sys_get_temp_dir().'/phpixie_config_handler';
        $this->handler = $this->handler();
    }
    
    /**
     * @covers ::read
     * @covers ::write
     * @covers ::<protected>
     */
    public function testReadWrite()
    {
        $this->readWriteTest($this->data);
    }
    
    protected function readWriteTest($data)
    {
        $this->handler->write($this->file, $data);
        $this->assertSame($data, $this->handler->read($this->file));
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
