<?php

namespace PHPixie\Tests\Config;

/**
 * @coversDefaultClass \PHPixie\Config\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $slice;
    protected $builder;
    
    public function setUp()
    {
        $this->slice   = $this->quickMock('\PHPixie\Slice');
        $this->builder = new \PHPixie\Config\Builder($this->slice);
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
        
    /**
     * @covers ::storages
     * @covers ::<protected>
     */
    public function testStorages()
    {
        $storages = $this->builder->storages();
        $this->assertInstance($storages, '\PHPixie\Config\Storages', array(
            'configBuilder' => $this->builder,
            'slice' => $this->slice,
        ));
        $this->assertSame($storages, $this->builder->storages());
    }
    
    /**
     * @covers ::formats
     * @covers ::<protected>
     */
    public function testFormats()
    {
        $formats = $this->builder->formats();
        $this->assertInstance($formats, '\PHPixie\Config\Formats');
        $this->assertSame($formats, $this->builder->formats());
    }

}