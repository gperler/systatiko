<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Entity;

use SystatikoTest\End2End\Asset\Component1\Contract\SampleInterface;

class DifferentReturnEntity implements SampleInterface
{

    /**
     * @Factory(
     *     namespace="SystatikoTest\End2End\Asset\Generated\Component1",
     *     returnType="SystatikoTest\End2End\Asset\Component1\Contract\SampleInterface"
     * )
     *
     */
    public function __construct()
    {
    }

}