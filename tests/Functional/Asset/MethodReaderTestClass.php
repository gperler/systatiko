<?php

declare(strict_types = 1);

namespace SystatikoTest\Functional\Asset;

use Systatiko\Reader\ExtendedReflectionClass;
use Systatiko\Reader\PHPType;
use Civis\Common\File as Test;

class MethodReaderTestClass
{

    /**
     * @FacadeExposition(namespace="\test\namespace")
     * @param Test|null $file
     * @param string[] $array
     * @param mixed $mixed
     *
     * @return Test[]|null
     */
    public function testMe(Test $file = null, array $array, $mixed)
    {
        return [];
    }

    /**
     * @param string $test
     * @param Test[]|null $array
     * @param PHPType[] $typeList
     *
     * @return PHPType[]
     */
    public function testMeToo(string $test = "hello", array $array = null, array $typeList) : array
    {
        return [];
    }

    public function methodWithOutDocBlock(string $test, int $test2)
    {
    }

    /**
     * @param ClassReaderTestClass $class
     *
     * @return ExtendedReflectionClass
     */
    public function sameNamespaceTest(ClassReaderTestClass $class) : ExtendedReflectionClass
    {

    }

    /**
     * @param MethodReaderTestClass $that
     */
    public function refersToThisClass(MethodReaderTestClass $that)
    {

    }

}