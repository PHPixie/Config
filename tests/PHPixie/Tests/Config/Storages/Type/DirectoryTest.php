<?php

namespace PHPixie\Tests\Config\Storages\Type;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\Type\Directory
 */
class DirectoryTest extends \PHPixie\Tests\Slice\Data\ImplementationTest
{
    protected $configBuilder;
    protected $dir;
    protected $parameters;
    
    protected $data = array(
        'meadows' => 5,
        'meadow' => array(
            'grass_type' => 6,
            'fairies' => array(
                'names' => array('Tinkerbell')
            ),
            'trees' => array(
                'oak' => array(
                    'fairy' => array('Trixie')
                )
            )
        ),
        'lake' => array(
            'mermaids' => array(
                'names' => array('Naiad')
            )
        )
    );
    
    protected $files = array(
        array('', 'forest.php', array('meadows' => 5, 'meadow' => 8)),
        array('field/', 'flowers.php', array('type' => 3)),
        array('forest/', 'meadow.php', array('grass_type' => 6, 'fairies' => false)),
        array('forest/lake/', 'mermaids.php', array('names' => array('Naiad'))),
        array('forest/meadow/', 'fairies.php', array('names' => array('%pixie%'))),
        array('forest/meadow/trees/', 'oak.php', array('fairy' => array('Trixie'))),
    );
    
    public function setUp()
    {
        $this->parameters = $this->quickMock('\PHPixie\Slice\Data');
        $this->method($this->parameters, 'getRequired', 'Tinkerbell', array('pixie'));
        $this->dir = sys_get_temp_dir().'/phpixie_config_test/';

        $this->removeDirs();
        
        foreach ($this->files as $file) {
            mkdir($this->dir.$file[0], 0777, true);
            file_put_contents($this->dir.$file[0].$file[1], "<?php\r\nreturn ".var_export($file[2], true).";");
        }

        parent::setUp();
    }

    public function tearDown()
    {
        $this->removeDirs();
    }

    protected function prepareGetDataSets()
    {
        $sets = array();
        
        $sets[] = array('get', array(), $this->data);
        $sets[] = array('get', array('meadows'), 5);
        $sets[] = array('getRequired', array('meadow.grass_type'), 6);
        
        $sets[] = array('getRequired', array('lake.mermaids'), array('names' => array('Naiad')));
        $sets[] = array('get', array('meadow'), array(
            'grass_type' => 6,
            'fairies' => array(
                'names' => array('Tinkerbell')
            ),
            'trees' => array(
                'oak' => array(
                    'fairy' => array('Trixie')
                )
            )
        ));
        
        $sets[] = array('get', array('meadow.trees.oak.fairy.0'), 'Trixie');
        
        $sets[] = array('get', array('meadow.grass_type.pixies', 'test'), 'test');
        $sets[] = array('getRequired', array('meadow.grass_type.pixies'), 'exception');
        $sets[] = array('getRequired', array('meadow.grass_type.pixies.name'), 'exception');
        
        return $sets;
    }


    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->sliceData->set('meadow.grass_type', 8);
        $this->assertEquals(8, $this->sliceData-> get('meadow.grass_type'));
        $this->sliceData->set('meadow.trail.length', 8);
        $this->assertEquals(8, $this->sliceData-> get('meadow.trail.length'));
        $this->assertSliceException(function () {
            $this->sliceData-> set('meadow.grass_type.pixies', 8);
        });

        $this->sliceData->set('meadow', array(
            'grass_type' => 6,
            'fairies' => array(
                'names' => array('Pixie')
            )
        ));
        $this->assertEquals('Pixie', $this->sliceData->get('meadow.fairies.names.0'));
        $this->assertEquals(1, $this->sliceData-> get('meadow.trees.oak', 1));
        $this->sliceData-> set(null, array('test' => 5));
        $this->assertEquals('5', $this->sliceData-> get('test'));
        
        $storage = $this->sliceData;
        $this->assertSliceException(function () use($storage) {
            $storage->set(null, 5);
        });
    }

    /**
     * @covers ::remove
     * @covers ::getData
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->sliceData->remove('meadow.grass_type');
        $this->assertEquals('test', $this->sliceData->get('meadow.grass_type', 'test'));
        $this->sliceData->remove('meadow.fairies');
        $this->assertEquals('test', $this->sliceData->get('meadow.fairies,names', 'test'));
        $this->sliceData->remove(null);
        $this->assertEquals('test', $this->sliceData->get('meadow', 'test'));
        $this->assertEquals('test', $this->sliceData->get(null, 'test'));
        
        $storage = $this->sliceData;
        $this->assertConfigException(function () use($storage) {
            $this->sliceData->getRequired(null);
        });
    }

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testFileCollisionException()
    {
        file_put_contents($this->dir.'/forest/meadow.json', '');
        
        $storage = $this->sliceData;
        $this->assertConfigException(function () use($storage) {
            $storage->get('meadow');
        });
    }
    
    /**
     * @covers ::keys
     * @covers ::<protected>
     */
    public function testKeys()
    {
        $this->assertSame(array('meadows', 'meadow', 'lake'), $this->sliceData->keys());
        $this->assertSame(array('names'), $this->sliceData->keys('meadow.fairies'));
        $this->assertSame(array(), $this->sliceData->keys('meadow.fairies.pixie'));
        
        $storage = $this->sliceData;
        $this->assertSliceException(function () use($storage) {
            $storage->keys('meadow.fairies.pixie', true);
        });
    }

    
    /**
     * @covers ::slice
     * @covers ::<protected>
     */
    public function testSlice()
    {
        $slice = $this->quickMock('\PHPixie\Slice\Type\Slice\Editable');
        
        $this->method($this->sliceBuilder, 'editableSlice', $slice, array($this->sliceData, 'test'), 0);
        $this->assertSame($slice, $this->sliceData->slice('test'));
        
        $this->method($this->sliceBuilder, 'editableSlice', $slice, array($this->sliceData, null), 0);
        $this->assertSame($slice, $this->sliceData->slice());
    }
    
    /**
     * @covers ::arraySlice
     * @covers ::<protected>
     */
    public function testArraySlice()
    {
        $slice = $this->getArraySlice();
        $this->method($this->sliceBuilder, 'arraySlice', $slice, array($this->data['meadow'], 'meadow'), 0);
        $this->assertSame($slice, $this->sliceData->arraySlice('meadow'));
        
        $slice = $this->getArraySlice();
        $this->method($this->sliceBuilder, 'arraySlice', $slice, array($this->data, null), 0);
        $this->assertSame($slice, $this->sliceData->arraySlice());
    }
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testIterator()
    {
        $iterator = $this->getIterator();
        $this->method($this->sliceBuilder, 'iterator', $iterator, array($this->sliceData), 0);
        $this->assertSame($iterator, $this->sliceData->getIterator());
    }

    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersist()
    {
        $persistedData = array(
            'grass_type' => 8,
            'fairies' => array(
                'names' => array('Pixie')
            )
        );
        $this->sliceData->set('meadow', $persistedData);
        $this->sliceData->persist();
    
        $this->assertIncludedFile(array(
            'names' => array('Pixie')
        ), $this->dir.'/forest/meadow/fairies.php');
        
        $this->assertEquals(true, file_exists($this->dir.'/forest/meadow/trees/oak.php'));
    }
    
    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersistRemove()
    {
        $persistedData = array(
            'grass_type' => 8,
            'fairies' => array(
                'names' => array('Pixie')
            )
        );
        $this->sliceData->set('meadow', $persistedData);
        $this->sliceData->persist(true);
    
        $this->assertIncludedFile(array(
            'names' => array('Pixie')
        ), $this->dir.'/forest/meadow/fairies.php');
        
        $this->assertEquals(false, file_exists($this->dir.'/forest/meadow/trees/oak.php'));
    }
    
    protected function assertIncludedFile($data, $file)
    {
        $copy = $this->dir.'/copy.txt';
        copy($file, $copy);
        $this->assertSame($data, include $copy);
        unlink($copy);
    }
    
    protected function removeDirs()
    {
        if(!is_dir($this->dir)) {
            return;
        }
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $this->dir,
                \RecursiveDirectoryIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $fileinfo) {
            $function = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $function($fileinfo->getRealPath());
        }
        
        rmdir($this->dir);
    }

    protected function assertConfigException($callback)
    {
        $this->assertException($callback, '\PHPixie\Config\Exception');
    }
    
    protected function sliceData()
    {
        $this->sliceBuilder  = $this->quickMock(
            '\PHPixie\Slice',
            array('editableSlice', 'iterator', 'arraySlice')
        );
        $this->configBuilder = new \PHPixie\Config($this->sliceBuilder);
        return $this->configBuilder->directory(
            $this->dir,
            'forest',
            'php',
            $this->parameters
        );
    }
}
