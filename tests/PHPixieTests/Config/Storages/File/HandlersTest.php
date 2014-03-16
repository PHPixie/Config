<?php

namespace PHPixieTests\Config\Storages\File;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\File\Handlers
 */
class HandlersTest extends \PHPixieTests\AbstractConfigTest
{
    protected $handlers;

    public function setUp()
    {
        $this->handlers = new \PHPixie\Config\Storages\File\Handlers;
    }

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $php = $this->handlers->get('PHP');
        $this->assertInstanceOf('\PHPixie\Config\Storages\File\Handler\PHP', $php);
        $this->assertEquals($php, $this->handlers->get('PHP'));
    }

    /**
     * @covers ::getForExtension
     * @covers ::<protected>
     */
    public function testGetForExtension()
    {
        $php = $this->handlers->getForExtension('php');
        $this->assertInstanceOf('\PHPixie\Config\Storages\File\Handler\PHP', $php);
        $this->assertEquals($php, $this->handlers->getForExtension('php'));
    }
}
