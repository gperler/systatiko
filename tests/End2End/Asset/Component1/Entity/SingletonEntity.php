<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Entity;

class SingletonEntity
{

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1", singleton=true)
     *
     */
    public function __construct()
    {

    }
}