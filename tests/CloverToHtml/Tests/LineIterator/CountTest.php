<?php

namespace CloverToHtml\Tests\LineIterator;

use CloverToHtml\LineIterator as Sut;

class CountTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $sUT = new Sut(__DIR__ . '/Fixtures/MyClass.php', array());

        $this->assertEquals(18, $sUT->count());
    }
}
