<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component1;

use SystatikoTest\End2End\Asset\Component1\Component1Configuration;
use SystatikoTest\End2End\Asset\Component1\Contract\SampleInterface;
use SystatikoTest\End2End\Asset\Component1\Entity\DifferentReturnEntity;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;
use SystatikoTest\End2End\Asset\Component1\Entity\SingletonEntity;
use SystatikoTest\End2End\Asset\Component1\Event\AsyncEvent;
use SystatikoTest\End2End\Asset\Component1\Event\C1Event;
use SystatikoTest\End2End\Asset\Component1\Model\DependencyInjection;
use SystatikoTest\End2End\Asset\Component1\Model\FacadeInjection;
use SystatikoTest\End2End\Asset\Component1\Model\ServiceClass;
use SystatikoTest\End2End\Asset\Generated\Backbone;

class Component1Factory
{

    /**
     * @var Backbone
     */
    protected $backbone;

    /**
     * @var Component1Facade
     */
    protected $component1Facade;

    /**
     * @var Component1Configuration
     */
    protected $component1Configuration;

    /**
     * @var SingletonEntity
     */
    protected $singletonEntity;

    /**
     * @var FacadeInjection
     */
    protected $facadeInjection;

    /**
     * @var ServiceClass
     */
    protected $serviceClass;

    /**
     * @param Backbone $backbone
     * 
     */
    public function __construct(Backbone $backbone)
    {
        $this->backbone = $backbone;
    }

    /**
     * 
     * @return Component1Configuration|null
     */
    public function getConfiguration()
    {
        if ($this->component1Configuration === null) {
            $this->component1Configuration = new Component1Configuration();
            $this->component1Configuration->setValueList($this->backbone->getComponentConfiguration("Component1"));
        }
        return $this->component1Configuration;
    }

    /**
     * 
     * @return Component1Facade
     */
    public function getComponent1Facade() : Component1Facade
    {
        if ($this->component1Facade === null) {
            $this->component1Facade = new Component1Facade($this->backbone, $this);
        }
        return $this->component1Facade;
    }

    /**
     * 
     * @return SampleInterface
     */
    public function newDifferentReturnEntity() : SampleInterface
    {
        return new DifferentReturnEntity();
    }

    /**
     * @param string $test
     * 
     * @return SampleEntity
     */
    public function newSampleEntity(string $test) : SampleEntity
    {
        return new SampleEntity($test);
    }

    /**
     * @param string $id
     * 
     * @return SingletonEntity
     */
    public function getSingletonEntity(string $id) : SingletonEntity
    {
        if ($this->singletonEntity === null) {
            $this->singletonEntity = new SingletonEntity($id);
        }
        return $this->singletonEntity;
    }

    /**
     * 
     * @return AsyncEvent
     */
    public function newAsyncEvent() : AsyncEvent
    {
        return new AsyncEvent();
    }

    /**
     * @param mixed $entity
     * 
     * @return DependencyInjection
     */
    public function newDependencyInjection($entity) : DependencyInjection
    {
        return new DependencyInjection($this, $this->getConfiguration(), $entity);
    }

    /**
     * 
     * @return FacadeInjection
     */
    public function getFacadeInjection() : FacadeInjection
    {
        if ($this->facadeInjection === null) {
            $this->facadeInjection = new FacadeInjection($this->backbone->getComponent2Facade());
        }
        return $this->facadeInjection;
    }

    /**
     * 
     * @return ServiceClass
     */
    public function getServiceClass() : ServiceClass
    {
        if ($this->serviceClass === null) {
            $this->serviceClass = new ServiceClass();
        }
        return $this->serviceClass;
    }

    /**
     * @param AsyncEvent $event
     * 
     * @return void
     */
    public function triggerAsyncEvent(AsyncEvent $event)
    {
        $this->backbone->dispatchOutboundAsynchronousEvent($event);
    }

    /**
     * @param C1Event $event
     * 
     * @return void
     */
    public function triggerC1Event(C1Event $event)
    {
        $this->backbone->getComponent2Facade()->eventHandler($event);
        $this->backbone->dispatchSynchronousEvent($event);
    }

    /**
     * 
     * @return string
     */
    public function exposeToAllFactories() : string
    {
        return $this->backbone->exposeToAllFactories();
    }
}