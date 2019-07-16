<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Civis\Common\File as Test;
use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;


/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 * @author Gregor Müller
 */
class MethodParameterTest
{

    const SOME_CONSTANT = 'some_value';

    /**
     * @Factory(
     *     namespace="SystatikoTest\End2End\Asset\Generated\Component1",
     *     singleton=false,
     * )
     * ServiceClass constructor.
     */
    public function __construct()
    {

    }


    /**
     * @FacadeExposition(namespace="\test\namespace")
     * @param Test|null $file
     * @param string[] $array
     * @param mixed $mixed
     * @param string $test
     *
     * @return Test[]|null
     */
    public function methodReaderTestMe(
        Test $file = null,
        array $array,
        $mixed): ?array
    {
        return [];
    }

    /**
     * @FacadeExposition()
     *
     * @param string $test
     * @param array|null $array
     * @param array $typeList
     * @return array
     */
    public function methodReaderTestMeToo(string $test = "null", array $array = null, array $typeList): array
    {
        return [];
    }

    /**
     * @FacadeExposition()
     *
     * @param int $test
     */
    public function numberTest(int $test = 2)
    {

    }

    /**
     * @FacadeExposition()
     *
     * @param int $test
     */
    public function numberTest2(int $test = 0)
    {

    }


    /**
     * @FacadeExposition()
     *
     * @param bool $test
     */
    public function boolTestTrue(bool $test = true)
    {

    }

    /**
     * @FacadeExposition()
     *
     * @param bool $test
     */
    public function boolTestFalse(bool $test = false)
    {

    }

    /**
     * @FacadeExposition()
     * @param array $test
     */
    public function arrayParameter(array $test = ["Gregor"])
    {

    }

    /**
     * @FacadeExposition()
     * @param array $test
     */
    public function arrayParameter2(array $test = [])
    {

    }

    /**
     * @FacadeExposition()
     *
     * @param string $test
     */
    public function constantParameter(string $test = self::SOME_CONSTANT)
    {

    }


    /**
     * @FacadeExposition()
     *
     * @return MethodParameterTest[]
     */
    public function returnTypeTest1(): array
    {
        return [];
    }

    /**
     * @FacadeExposition()
     *
     * @return MethodParameterTest
     */
    public function returnTypeTest2(): MethodParameterTest
    {
        return $this;
    }

    /**
     * @FacadeExposition()
     *
     * @return MethodParameterTest|null
     */
    public function returnTypeTest3(): ?MethodParameterTest
    {
        return $this;
    }

}