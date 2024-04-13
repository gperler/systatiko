<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Entity;

use Systatiko\Annotation\Factory;

class SampleEntity
{

    public $test;

    /**
     *
     * SampleEntity constructor.
     *
     * @param string $test
     */
    #[Factory(namespace: "SystatikoTest\End2End\Asset\Generated\Component1")]
    public function __construct(string $test)
    {
        $this->test = $test;
    }
}