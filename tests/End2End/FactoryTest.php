<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End;

use SystatikoTest\End2End\Asset\Generated\FacadeLocator;

class FactoryTest extends End2EndTest
{

    /**
     *
     */
    public function testInstanceFactory()
    {
        $locator = FacadeLocator::getInstance();
        $factory = $locator->getComponent1Factory();

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
        $locator = FacadeLocator::getInstance();
        $factory = $locator->getComponent1Factory();

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
        $locator = FacadeLocator::getInstance();
        $factory = $locator->getComponent1Factory();

        $facade = $factory->getComponent1Facade();

        $this->assertNotNull($facade);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\Component1\Component1Facade', $facade);

    }

    /**
     *
     */
    public function testContextDependant()
    {
        $locator = FacadeLocator::getInstance();
        $locator->setContext('default');

        $factory2 = $locator->getComponent2Factory();
        $baseEntity = $factory2->newBaseEntity();

        $this->assertNotNull($baseEntity);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\BaseEntity', $baseEntity);
        $this->assertNotInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\OverWriteEntity', $baseEntity);

        $locator->setContext('X');
        $baseEntity = $factory2->newBaseEntity();
        $this->assertNotNull($baseEntity);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\BaseEntity', $baseEntity);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component2\Entity\OverWriteEntity', $baseEntity);

    }

    public function testConfiguration()
    {
        $locator = FacadeLocator::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "Asset" . DIRECTORY_SEPARATOR . "facade.locator.config.json");

        $factory = $locator->getComponent1Factory();

        $config = $factory->getConfiguration();
        $this->assertNotNull($config);

        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component1\Component1Configuration', $config);

        $this->assertSame("Jamie Woon", $config->getTestValue());
    }
}