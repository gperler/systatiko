<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Entity;

class SampleEntity
{

    public $test;

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
     * SampleEntity constructor.
     *
     * @param string $test
     */
    public function __construct(string $test)
    {
        $this->test = $test;
    }
}