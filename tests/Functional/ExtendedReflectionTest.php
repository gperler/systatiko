<?php

declare(strict_types = 1);

namespace SystatikoTest\Functional;

use Systatiko\Reader\ExtendedReflectionClass;
use SystatikoTest\Functional\Asset\ExtendedReflectionTestClass;

class ExtendedReflectionTest extends \PHPUnit_Framework_TestCase
{

    public function testUseStatement()
    {
        $class = new ExtendedReflectionClass(ExtendedReflectionTestClass::class);

        $useList = $class->getUseStatementList();

        $this->assertSame(3, sizeof($useList));

        $this->assertSame('Nitria\Method', $useList[0]["class"]);
        $this->assertSame('Nitria\Method', $useList[0]["as"]);

        $this->assertSame('Nitria\MethodParameter', $useList[1]["class"]);
        $this->assertSame('Test', $useList[1]["as"]);

        $this->assertSame('DOMElement', $useList[2]["class"]);
        $this->assertSame('DOMElement', $useList[2]["as"]);

    }

}