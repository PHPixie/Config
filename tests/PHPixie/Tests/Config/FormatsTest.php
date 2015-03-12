<?php

namespace PHPixie\Tests\Config;

/**
 * @coversDefaultClass \PHPixie\Config\Formats
 */
class FormatsTest extends \PHPixie\Test\Testcase
{
    protected $formats;
    
    public function setUp()
    {
        $this->formats = new \PHPixie\Config\Formats();
    }
    
    /**
     * @covers ::php
     * @covers ::<protected>
     */
    public function testPhp()
    {
        $php = $this->formats->php();
        $this->assertInstance($php, '\PHPixie\Config\Formats\Format\PHP');
        $this->assertSame($php, $this->formats->php());
    }
    
    /**
     * @covers ::json
     * @covers ::<protected>
     */
    public function testJson()
    {
        $json = $this->formats->json();
        $this->assertInstance($json, '\PHPixie\Config\Formats\Format\JSON');
        $this->assertSame($json, $this->formats->json());
    }
    
    /**
     * @covers ::getByFilename
     * @covers ::<protected>
     */
    public function testGetByFileName()
    {
        $json = $this->formats->getByFilename('s.JsOn');
        $this->assertSame($this->formats->json(), $json);
        
        $php = $this->formats->getByFilename('s.pHp');
        $this->assertSame($this->formats->php(), $php);
    }
}