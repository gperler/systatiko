<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated;

use Civis\Common\File;
use SystatikoTest\End2End\Asset\BackboneProject;
use SystatikoTest\End2End\Asset\Component1\Event\AsyncEvent;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Facade;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory;
use SystatikoTest\End2End\Asset\Generated\Component2\Component2Facade;
use SystatikoTest\End2End\Asset\Generated\Component2\Component2Factory;
use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Exception\EventNotDefinedException;

class Backbone extends BackboneProject
{

    /**
     * @var Backbone|null
     */
    protected static ?Backbone $instance = null;

    /**
     * @param string|null $configFileName
     * 
     * @return Backbone
     */
    public static function getInstance(string $configFileName = null): Backbone
    {
        if (self::$instance === null) {
            self::$instance = new Backbone();
        }
        if ($configFileName !== null) {
            self::$instance->setConfigurationFile(new File($configFileName));
        }
        return self::$instance;
    }


    /**
     * @var Component1Factory|null
     */
    protected ?Component1Factory $component1Factory = null;

    /**
     * @var Component2Factory|null
     */
    protected ?Component2Factory $component2Factory = null;

    /**
     * 
     * @return Component1Facade
     */
    public function getComponent1Facade(): Component1Facade
    {
        return $this->getComponent1Factory()->getComponent1Facade();
    }


    /**
     * 
     * @return Component1Factory
     */
    public function getComponent1Factory(): Component1Factory
    {
        if ($this->component1Factory === null) {
            $this->component1Factory = new Component1Factory($this); 
        }
        return $this->component1Factory;
    }


    /**
     * 
     * @return Component2Facade
     */
    public function getComponent2Facade(): Component2Facade
    {
        return $this->getComponent2Factory()->getComponent2Facade();
    }


    /**
     * 
     * @return Component2Factory
     */
    public function getComponent2Factory(): Component2Factory
    {
        if ($this->component2Factory === null) {
            $this->component2Factory = new Component2Factory($this); 
        }
        return $this->component2Factory;
    }


    /**
     * @param string $eventName
     * @param array $payload
     * 
     * @return AsynchronousEvent
     * @throws EventNotDefinedException
     */
    public function newAsynchronousEvent(string $eventName, array $payload): AsynchronousEvent
    {
        switch ($eventName) {
            case "com.test.myevent.event1":
                $event = $this->getComponent1Factory()->newAsyncEvent();
                break;
            default:
                throw new EventNotDefinedException($eventName . " not defined");
        }
        $event->fromPayload($payload);
        return $event;
    }


    /**
     * @param AsynchronousEvent $event
     * 
     * @return void
     */
    public function dispatchInboundAsynchronousEvent(AsynchronousEvent $event): void
    {
        if ($event instanceof AsyncEvent) {
            $this->getComponent2Facade()->handleAsyncEvent($event);
        }
    }


    /**
     * 
     * @return void
     */
    public function resetSingleton(): void
    {
        $this->component1Factory = null;
        $this->component2Factory = null;
    }

}