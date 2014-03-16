<?php

namespace PHPixieTests\Config\Storage;

/**
 * @coversDefaultClass \PHPixie\Config\Storage\Slice
 */
class SliceTest extends \PHPixieTests\Config\SliceTest
{
    protected $storage;

    public function setUp()
    {
        $this->storage = $this->getMock('\PHPixie\Config\Storage', array('get', 'set', 'slice', 'remove', 'key'), array(), '' , false);
        $this->slice = $this->getSlice();
    }

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGetDefault()
    {
        $this->storage
                    ->expects($this->any())
                    ->method('get')
                    ->with ('test', 'test')
                    ->will($this->returnValue(5));
        $this->assertEquals(5, $this->slice->get('test', 'test'));
    }

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGetDeep()
    {
        $this->slice = $this->getSlice('pixie');
        $this->storage
                    ->expects($this->any())
                    ->method('get')
                    ->with ('pixie.test')
                    ->will($this->returnValue(5));
        $this->assertEquals(5, $this->slice->get('test'));
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

    protected function getSlice($key = null)
    {
        return new \PHPixie\Config\Storage\Slice($this->config, $this->storage, $key);
    }
}
