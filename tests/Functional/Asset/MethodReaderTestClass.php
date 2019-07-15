<?php

declare(strict_types=1);

namespace SystatikoTest\Functional\Asset;

use Civis\Common\File as Test;
use Systatiko\Annotation\FacadeExposition;
use Systatiko\Exception\EventNotDefinedException;
use Systatiko\Reader\ExtendedReflectionClass;
use Systatiko\Reader\PHPType;

class MethodReaderTestClass
{

    /**
     * @FacadeExposition(namespace="\test\namespace")
     * @param Test|null $file
     * @param string[] $array
     * @param mixed $mixed
     * @param string $test
     *
     * @return Test[]|null
     */
    public function testMe(Test $file = null, array $array, $mixed): ?array
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
    public function testMeToo(string $test = "null", array $array = null, array $typeList): array
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
    public function sameNamespaceTest(ClassReaderTestClass $class): ExtendedReflectionClass
    {

    }

    /**
     * @param MethodReaderTestClass $that
     */
    public function refersToThisClass(MethodReaderTestClass $that)
    {

    }

    /**
     * @return null|string
     * @throws TestException1
     * @throws TestException2
     * @throws EventNotDefinedException
     */
    public function testOptionalReturn(): ?string
    {
        if ($this->sameNamespaceTest(null) === null) {
            throw new TestException1();
        }
        if ($this->sameNamespaceTest(new ClassReaderTestClass(null, null)) === null) {
            throw new TestException2();
        }

        if (time() > 0) {
            throw new EventNotDefinedException();
        }

        return 'Hello';
    }

}