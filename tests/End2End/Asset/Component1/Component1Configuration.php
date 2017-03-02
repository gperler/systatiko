<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1;

use Systatiko\Runtime\ComponentConfigurationBase;

/**
 * @Configuration(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 */
class Component1Configuration extends ComponentConfigurationBase
{

    const TEST_VALUE = "testValue";

    /**
     * @return null|string
     */
    public function getTestValue()
    {
        return $this->getConfigurationValue(self::TEST_VALUE);
    }
}

