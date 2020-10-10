<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End;

use Civis\Common\ArrayUtil;
use SystatikoTest\End2End\Asset\Generated\Backbone;
use SystatikoTest\End2End\Asset\TestAsyncEventHandler;
use SystatikoTest\End2End\Asset\Component1\Constant\Role;

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

    public function testFacadeInjection()
    {
        $backbone = Backbone::getInstance();
        $facade = $backbone->getComponent1Facade();
        $this->assertTrue($facade->getInjectionStatus());
        $this->assertTrue($facade->getFacadeInjectionStatus());

        $factory = $backbone->getComponent1Factory();

        $sampleEntity = $factory->newSampleEntity("hello");
        $service = $factory->getNoInjection($sampleEntity);
        $this->assertSame("hello", $service->getTestValue());
    }

    public function testAsyncEventDispatchOutbound()
    {
        $backbone = Backbone::getInstance();

        $testHandler = new TestAsyncEventHandler();
        $backbone->addOutboundAsynchronousEventHandler($testHandler);

        $facade = $backbone->getComponent1Facade();
        $facade->triggerAsyncEvent([
            "hello" => "async"
        ]);

        $receivedArray = $testHandler->getPayload();
        $this->assertSame("async", $receivedArray["hello"]);

    }

    public function testAsyncEventDispatchInbound()
    {
        $backbone = Backbone::getInstance();
        $event = $backbone->newAsynchronousEvent("com.test.myevent.event1", []);

        $this->assertNotNull($event);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Component1\Event\AsyncEvent', $event);

        $backbone->dispatchInboundAsynchronousEvent($event);

        $payload = $event->getPayload();
        $this->assertNotNull($payload);
        $this->assertTrue($payload["async_handler"]);

    }
}