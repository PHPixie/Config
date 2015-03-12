<?php

namespace PHPixie\Tests\Config;

/**
 * @coversDefaultClass \PHPixie\Config\Storages
 */
class StoragesTest extends \PHPixie\Test\Testcase
{
    protected $configBuilder;
    protected $slice;
    
    protected $storages;
    
    protected $formats;
    
    public function setUp()
    {
        $this->configBuilder = $this->quickMock('\PHPixie\Config\Builder');
        $this->slice   = $this->quickMock('\PHPixie\Slice');
        
        $this->storages = new \PHPixie\Config\Storages(
            $this->configBuilder,
            $this->slice
        );
        
        $this->formats = $this->quickMock('\PHPixie\Config\Formats');
        $this->method($this->configBuilder, 'formats', $this->formats, array());
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
        
    /**
     * @covers ::file
     * @covers ::<protected>
     */
    public function testFile()
    {
        $file = 'pixie.php';
        $fileStorage = $this->storages->file($file);
        $this->assertInstance($fileStorage, '\PHPixie\Config\Storages\Type\File', array(
            'sliceBuilder' => $this->slice,
            'formats'      => $this->formats
        ));
    }
    
    /**
     * @covers ::directory
     * @covers ::<protected>
     */
    public function testDirectory()
    {
        $dir  = 'pixie';
        $name = 'fairy';
        $directoryStorage = $this->storages->directory($dir, $name);
        $this->assertInstance($directoryStorage, '\PHPixie\Config\Storages\Type\Directory', array(
            'storages'      => $this->storages,
            'sliceBuilder'  => $this->slice,
            'directory'     => $dir,
            'name'          => $name,
            'defaultFormat' => 'php',
        ));
    }

}