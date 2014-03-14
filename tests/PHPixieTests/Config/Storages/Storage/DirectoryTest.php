<?php

namespace PHPixieTests\Config\Storages\Storage;

class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    protected $config;
    protected $storage;
    protected $dir;
    protected $files = array(
        array('', 'forest.php', array('meadows' => 5, 'meadow' => false)),
        array('field/', 'flowers.php', array('type' => 3)),
        array('forest/', 'meadow.php', array('grass_type' => 5, 'fairies' => false)),
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
        
        $this->config = new \PHPixie\Config;
        $this->storage = new \PHPixie\Config\Storages\Storage\Directory($this->config, $this->dir, 'forest');
    }
    
    public function tearDown()
    {
        $this->removeDirs();
    }
    
    public function testGet()
    {
        $this->assertEquals(4, $this->storage->get('forest.meadows'));
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