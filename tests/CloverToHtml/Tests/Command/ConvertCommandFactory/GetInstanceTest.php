<?php

namespace CloverToHtml\Tests\Command\ConvertCommandFactory;

use CloverToHtml\Command\ConvertCommandFactory;
use Symfony\Component\Console\Output\NullOutput;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            '\CloverToHtml\Command\ConvertCommand',
            ConvertCommandFactory::getInstance()
        );
    }
}
