<?php

namespace PHPixie\Tests\Config\Formats\Format;

/**
 * @coversDefaultClass \PHPixie\Config\Formats\Format\PHP
 */
class PHPTest extends \PHPixie\Tests\Config\Formats\FormatTest
{
    protected function handler()
    {
        return new \PHPixie\Config\Formats\Format\PHP();
    }

}
