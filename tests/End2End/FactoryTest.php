<?php

declare(strict_types=1);

namespace SystatikoTest\End2End;

use SystatikoTest\End2End\Asset\Component1\Entity\InjectContext;
use SystatikoTest\End2End\Asset\Generated\Backbone;

class FactoryTest extends End2EndTest
{

    /**
     *
     */
    public function testInstanceFactory()
    {
        $backbone = Backbone::getInstance();
        $factory = $backbone->getComponent1Factory();

        $sample = $factory->newSampleEntity("Dorfmeister");

        $this->assertNotNull($sample);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity', $sample);

        $this->assertSame("Dorfmeister", $sample->test);
    }

    /**
     *
     */
    public function testSingletonFactory()
    {
        $backbone = Backbone::getInstance();
        $factory = $backbone->getComponent1Factory();

        $singleton = $factory->getSingletonEntity("Dorfmeister");

        $this->assertNotNull($singleton);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component1\Entity\SingletonEntity', $singleton);
        $this->assertSame("Dorfmeister", $singleton->id);

        $singleton = $factory->getSingletonEntity("XYZ");
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component1\Entity\SingletonEntity', $singleton);
        $this->assertSame("Dorfmeister", $singleton->id);
    }

    /**
     *
     */
    public function testFacadeFactoryAccess()
    {
        $backbone = Backbone::getInstance();
        $factory = $backbone->getComponent1Factory();

        $facade = $factory->getComponent1Facade();

        $this->assertNotNull($facade);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\Component1\Component1Facade', $facade);

    }

    /**
     *
     */
    public function testContextDependant()
    {
        $backbone = Backbone::getInstance();
        $backbone->setContext('default');

        $factory2 = $backbone->getComponent2Factory();
        $baseEntity = $factory2->newBaseEntity();

        $this->assertNotNull($baseEntity);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\BaseEntity', $baseEntity);
        $this->assertNotInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\OverWriteEntity', $baseEntity);

        $backbone->setContext('X');
        $baseEntity = $factory2->newBaseEntity();
        $this->assertNotNull($baseEntity);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\BaseEntity', $baseEntity);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\OverWriteEntity', $baseEntity);

    }

    public function testConfiguration()
    {
        $backbone = Backbone::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "Asset" . DIRECTORY_SEPARATOR . "backbone.config.json");

        $factory = $backbone->getComponent1Factory();

        $config = $factory->getConfiguration();
        $this->assertNotNull($config);

        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component1\Component1Configuration', $config);

        $this->assertSame("Jamie Woon", $config->getTestValue());
    }

    public function testInjectConfiguration()
    {
        $backbone = Backbone::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "Asset" . DIRECTORY_SEPARATOR . "backbone.config.json");

        $facade2 = $backbone->getComponent2Facade();

        $this->assertSame(InjectContext::HELLO_MESSAGE, $facade2->injectedSayHello());;

    }
}