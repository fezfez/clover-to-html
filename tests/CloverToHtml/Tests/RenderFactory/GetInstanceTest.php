<?php

namespace CloverToHtml\Tests\RenderFactory;

use CloverToHtml\RenderFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            '\CloverToHtml\Render',
            RenderFactory::getInstance()
        );
    }
}
