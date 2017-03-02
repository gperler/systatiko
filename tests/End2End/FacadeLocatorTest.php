<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End;

use Civis\Common\ArrayUtil;
use SystatikoTest\End2End\Asset\Generated\FacadeLocator;

class FacadeLocatorTest extends End2EndTest
{

    /**
     *
     */
    public function testFacadeLocator()
    {
        $locator = FacadeLocator::getInstance();
        $this->assertNotNull($locator);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\FacadeLocator', $locator);
    }

    /**
     *
     */
    public function testConfigFile()
    {
        $locator = FacadeLocator::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "Asset" . DIRECTORY_SEPARATOR . "facade.locator.config.json");
        $config = $locator->getComponentConfiguration("Common");

        $this->assertNotNull($config);
        $this->assertTrue(is_array($config));
        $this->assertSame("config", ArrayUtil::getFromArray($config, "x"));
    }

    /**
     *
     */
    public function testContext()
    {
        $locator = FacadeLocator::getInstance();
        $this->assertNull($locator->getContext());

        $locator->setContext("C1");
        $this->assertSame("C1", $locator->getContext());
    }

    /**
     *
     */
    public function testFactoryAccess()
    {
        $locator = FacadeLocator::getInstance();
        $factory = $locator->getComponent1Factory();

        $this->assertNotNull($factory);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory', $factory);
    }

    /**
     *
     */
    public function testFacadeAccess()
    {
        $locator = FacadeLocator::getInstance();
        $facade = $locator->getComponent1Facade();

        $this->assertNotNull($facade);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\Component1\Component1Facade', $facade);

    }
}