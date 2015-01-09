<?php

namespace PHPixieTests\Config;

/**
 * @coversDefaultClass \PHPixie\Config\Slice
 */
abstract class SliceTest extends \PHPixieTests\AbstractConfigTest
{
    protected $config;

    public function setUp()
    {
        $this->config = $this->getMock('\PHPixie\Config', array('buildSlice'));
    }

    /**
     * @covers ::key
     */
    public function testKey()
    {
        $this->assertEquals(null, $this->slice()->key());
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
        $this->assertEquals('test.key', $slice->fullKey('key'));
        $this->assertEquals('test', $slice->fullKey());
    }
    
    /**
     * @covers ::get
     * @covers ::getRequired
     * @covers ::getData
     * @covers ::<protected>
     */
    public function testGet()
    {
        $sets = $this->prepareGetDataSets();
        
        foreach($sets as $set) {
            $callable = array($this->slice(), $set[0]);
            
            if($set[2] == 'exception') {
                $this->assertException(function() use($callable, $set) {
                    call_user_func_array($callable, $set);
                });
                
            }else{
                $this->assertSame($set[2], call_user_func_array($callable, $set[1]));
            }
        }
    }
    
    protected function assertException($callback)
    {
        $except = false;
        try {
            $callback();
        } catch (\PHPixie\Config\Exception $e) {
            $except = true;
        }
        $this->assertEquals(true, $except);
    }
    
    abstract protected function slice();
    abstract protected function prepareGetDataSets();
    abstract protected function getSlice($key = null);
    
}
