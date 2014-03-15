<?php

namespace PHPixieTests\Config\Storages\Storage;

/**
 * @coversDefaultClass \PHPixie\Config\Storages\Directory
 */
class DirectoryTest extends \PHPixieTests\Config\Storage\PersistableTest
{
    protected $storage;
    protected $dir;
    protected $files = array(
        array('', 'forest.php', array('meadows' => 5, 'meadow' => 8)),
        array('field/', 'flowers.php', array('type' => 3)),
        array('forest/', 'meadow.php', array('grass_type' => 6, 'fairies' => false)),
        array('forest/lake/', 'mermaids.php', array('names' => array('Naiad'))),
        array('forest/meadow/', 'fairies.php', array('names' => array('Tinkerbell'))),
        array('forest/meadow/trees/', 'oak.php', array('fairy' => array('Trixie'))),
    );
    public function setUp()
    {
        $this->dir = sys_get_temp_dir().'/phpixie_config_test/';
        
        $this->removeDirs();
        
        foreach($this->files as $file) {
            mkdir($this->dir.$file[0], 0777, true);
            file_put_contents($this->dir.$file[0].$file[1], "<?php\r\nreturn ".var_export($file[2], true).";");
        }
        
        parent::setUp();
    }
    
    public function tearDown()
    {
        $this->removeDirs();
    }
    
    /**
     * @covers ::persist
     * @covers ::<protected>
     */
    public function testPersist()
    {
        parent::testPersist();
        $this->assertEquals(array(
            'names' => array('Pixie')
        ), include($this->dir.'/forest/meadow/fairies.php'));
        $this->assertEquals(false, file_exists($this->dir.'/forest/meadow/trees/oak.php'));
    }
    
    protected function getStorage($key = null) {
        return new \PHPixie\Config\Storages\Directory($this->config, $this->dir, 'forest', 'php', $key);
    }
    
    protected function removeDirs()
    {
        foreach(array_reverse($this->files) as $file) {
            if(file_exists($this->dir.$file[0].$file[1]))
                unlink($this->dir.$file[0].$file[1]);
            
            if(file_exists($this->dir.$file[0]))
                rmdir($this->dir.$file[0]);
        }
    }
}