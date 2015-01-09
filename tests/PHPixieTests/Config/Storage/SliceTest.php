<?php

namespace PHPixieTests\Config\Storage;

/**
 * @coversDefaultClass \PHPixie\Config\Storage\Slice
 */
class SliceTest extends \PHPixieTests\Config\SliceTest
{
    protected $storage;
    protected $slice;

    public function setUp()
    {
        $this->storage = $this->getMock('\PHPixie\Config\Storage', array(), array(), '' , false);
        $this->slice = $this->getSlice();
    }

    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->storage
                    ->expects($this->any())
                    ->method('set')
                    ->with ('test', 7);
        $this->slice->set('test', 7);
    }

    /**
     * @covers ::slice
     * @covers ::<protected>
     */
    public function testSlice()
    {
        $this->storage
                    ->expects($this->any())
                    ->method('slice')
                    ->with ('test')
                    ->will($this->returnValue(5));
        $this->assertEquals(5, $this->slice->slice('test'));
    }

    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->storage
                    ->expects($this->any())
                    ->method('remove')
                    ->with ('test');
        $this->slice->remove('test');
    }
    
    /**
     * @covers ::keys
     * @covers ::<protected>
     */
    public function testKeys()
    {
        $this->storage
                    ->expects($this->at(0))
                    ->method('keys')
                    ->with ('test', false);
        
        $this->slice->keys('test');
        
        $this->storage
                    ->expects($this->at(0))
                    ->method('keys')
                    ->with ('test', true);
        
        $this->slice->keys('test', true);

    }

    /**
     * @covers ::storageKey
     * @covers ::<protected>
     */
    public function testStorageKey()
    {
        $this->assertEquals('test', $this->getSlice()->storageKey('test'));
        $slice = $this->getSlice('test');
        $this->assertEquals('test.key', $slice-> storageKey('key'));
        $this->assertEquals('test', $slice->storageKey());
    }

    /**
     * @covers ::fullKey
     * @covers ::<protected>
     */
    public function testFullKey()
    {
        $this->assertEquals('test', $this->getSlice()->fullKey('test'));
        $slice = $this->getSlice('test');
        $this->storage
                    ->expects($this->any())
                    ->method('key')
                    ->will($this->returnValue('pixie'));
        $this->assertEquals('pixie.test.key', $slice-> fullKey('key'));
    }

    protected function prepareGetDataSets()
    {
        $sets = array();
        
        $this->storage
                    ->expects($this->at(0))
                    ->method('getData')
                    ->with('pixie', false, null)
                    ->will($this->returnValue('test'));
        
        $sets[]= array('get', array('pixie'), 'test');
        
        $this->storage
                    ->expects($this->at(1))
                    ->method('getData')
                    ->with('pixie', false, 5)
                    ->will($this->returnValue('test'));
        
        $sets[]= array('get', array('pixie', 5), 'test');
        
        $this->storage
                    ->expects($this->at(2))
                    ->method('getData')
                    ->with('pixie', true, null)
                    ->will($this->returnValue('test'));
        
        $sets[]= array('getRequired', array('pixie'), 'test');
        
        $this->storage
                    ->expects($this->at(3))
                    ->method('getData')
                    ->with('pixie', true, null)
                    ->will($this->throwException(new \PHPixie\Config\Exception()));
        
        $sets[]= array('getRequired', array('pixie'), 'exception');
        
        return $sets;
    }
    
    protected function slice()
    {
        return $this->slice;
    }
    
    protected function getSlice($key = null)
    {
        return new \PHPixie\Config\Storage\Slice($this->config, $this->storage, $key);
    }
    
}
