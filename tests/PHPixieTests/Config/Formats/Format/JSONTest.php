<?php

namespace PHPixieTests\Config\Formats\Format;

/**
 * @coversDefaultClass \PHPixie\Config\Formats\Format\JSON
 */
class JSONTest extends \PHPixieTests\Config\Formats\FormatTest
{
    protected function handler()
    {
        return new \PHPixie\Config\Formats\Format\JSON();
    }

}
