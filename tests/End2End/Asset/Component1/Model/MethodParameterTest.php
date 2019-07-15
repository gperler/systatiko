<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Civis\Common\File as Test;
use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use Systatiko\Reader\PHPType;


/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 * @author Gregor Müller
 */
class MethodParameterTest
{


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

}