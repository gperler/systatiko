<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Civis\Common\File as Test;
use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;


/**
 * @author Gregor Müller
 */
#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1')]
class MethodParameterTest
{

    const SOME_CONSTANT = 'some_value';

    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1', singleton: false)] // ServiceClass constructor.
    public function __construct()
    {

    }


    /**
     * @param Test|null $file
     * @param string[] $array
     * @param mixed $mixed
     * @param string $test
     * @return Test[]|null
     */
    #[FacadeExposition(namespace: '\test\namespace')]
    public function methodReaderTestMe(
        Test $file = null,
        array $array,
        $mixed): ?array
    {
        return [];
    }

    /**
     *
     * @param string $test
     * @param array|null $array
     * @param array $typeList
     * @return array
     */
    #[FacadeExposition]
    public function methodReaderTestMeToo(string $test = "null", array $array = null, array $typeList): array
    {
        return [];
    }

    /**
     * @param int $test
     */
    #[FacadeExposition]
    public function numberTest(int $test = 2)
    {

    }

    /**
     * @param int $test
     */
    #[FacadeExposition]
    public function numberTest2(int $test = 0)
    {

    }


    /**
     * @param bool $test
     */
    #[FacadeExposition]
    public function boolTestTrue(bool $test = true)
    {

    }

    /**
     * @param bool $test
     */
    #[FacadeExposition]
    public function boolTestFalse(bool $test = false)
    {

    }

    /**
     * @param array $test
     */
    #[FacadeExposition]
    public function arrayParameter(array $test = ["Gregor"])
    {

    }

    /**
     * @param array $test
     */
    #[FacadeExposition]
    public function arrayParameter2(array $test = [])
    {

    }

    /**
     * @param string $test
     */
    #[FacadeExposition]
    public function constantParameter(string $test = self::SOME_CONSTANT)
    {

    }


    /**
     * @return MethodParameterTest[]
     */
    #[FacadeExposition]
    public function returnTypeTest1(): array
    {
        return [];
    }

    /**
     * @return MethodParameterTest
     */
    #[FacadeExposition]
    public function returnTypeTest2(): MethodParameterTest
    {
        return $this;
    }

    /**
     * @return MethodParameterTest|null
     */
    #[FacadeExposition]
    public function returnTypeTest3(): ?MethodParameterTest
    {
        return $this;
    }

}