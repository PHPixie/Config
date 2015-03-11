<?php

namespace PHPixieTests\Config\Formats\Format;

/**
 * @coversDefaultClass \PHPixie\Config\Formats\Format\PHP
 */
class PHPTest extends \PHPixieTests\Config\Formats\FormatTest
{
    protected function handler()
    {
        return new \PHPixie\Config\Formats\Format\PHP();
    }

}
