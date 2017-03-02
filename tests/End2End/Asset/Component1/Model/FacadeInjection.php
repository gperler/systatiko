<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use SystatikoTest\End2End\Asset\Generated\Component2\Component2Facade;

class FacadeInjection
{


    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1", singleton=true)
     * ServiceClass constructor.
     */
    public function __construct(Component2Facade $facade)
    {
    }
}