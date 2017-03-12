<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End;

use Civis\Common\ArrayUtil;
use SystatikoTest\End2End\Asset\Generated\Backbone;

class BackboneTest extends End2EndTest
{

    /**
     *
     */
    public function testBackboneSingleton()
    {
        $backbone = Backbone::getInstance();
        $this->assertNotNull($backbone);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\Backbone', $backbone);
    }

    /**
     *
     */
    public function testConfigFile()
    {
        $backbone = Backbone::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "Asset" . DIRECTORY_SEPARATOR . "backbone.config.json");
        $config = $backbone->getComponentConfiguration("Common");

        $this->assertNotNull($config);
        $this->assertTrue(is_array($config));
        $this->assertSame("config", ArrayUtil::getFromArray($config, "x"));
    }

    /**
     *
     */
    public function testContext()
    {
        $backbone = Backbone::getInstance();
        $this->assertNull($backbone->getContext());

        $backbone->setContext("C1");
        $this->assertSame("C1", $backbone->getContext());
    }

    /**
     *
     */
    public function testFactoryAccess()
    {
        $backbone = Backbone::getInstance();
        $factory = $backbone->getComponent1Factory();

        $this->assertNotNull($factory);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory', $factory);
    }

    /**
     *
     */
    public function testFacadeAccess()
    {
        $backbone = Backbone::getInstance();
        $facade = $backbone->getComponent1Facade();

        $this->assertNotNull($facade);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\Component1\Component1Facade', $facade);

    }
}