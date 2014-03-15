<?php

namespace PHPixieTests\Config;

/**
 * @coversDefaultClass \PHPixie\Config\Slice
 */
abstract class SliceTest extends \PHPixieTests\AbstractConfigTest
{
    protected $config;
    
    public function setUp() {
        $this->config = $this->getMock('\PHPixie\Config', array('buildSlice'));
    }
    
    /**
     * @covers ::key
     */
    public function testKey()
    {
        $this->assertEquals(null, $this->getSlice()->key());
        $this->assertEquals('test', $this->getSlice('test')->key());
    }
    
    public function testConstruct()
    {
        $this->getSlice();
    }
    
    /**
     * @covers ::fullKey
     */
    public function testFullKey()
    {
        $this->assertEquals('test', $this->getSlice()->fullKey('test'));
        $slice = $this->getSlice('test');
        $this->assertEquals('test.key', $slice-> fullKey('key'));
        $this->assertEquals('test', $slice->fullKey());
    }
    
    abstract protected function getSlice($key = null);
}