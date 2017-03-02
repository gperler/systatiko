<?php

declare(strict_types = 1);

namespace SystatikoTest\Functional;

use Systatiko\Reader\ExtendedReflectionClass;

class ExtendedReflectionTest extends \PHPUnit_Framework_TestCase
{

    public function testUseStatement()
    {
        $class = new ExtendedReflectionClass('SystatikoTest\Functional\Asset\ExtendedReflectionTestClass');

        $useList = $class->getUseStatements();

        $this->assertSame(2, sizeof($useList));

        $this->assertSame('Nitria\Method', $useList[0]["class"]);
        $this->assertSame('Nitria\Method', $useList[0]["as"]);

        $this->assertSame('Nitria\MethodParameter', $useList[1]["class"]);
        $this->assertSame('Test', $useList[1]["as"]);

    }

}