<?php

namespace CloverToHtml\Tests\ConverterFactory;

use CloverToHtml\ConverterFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            '\CloverToHtml\Converter',
            ConverterFactory::getInstance()
        );
    }
}
