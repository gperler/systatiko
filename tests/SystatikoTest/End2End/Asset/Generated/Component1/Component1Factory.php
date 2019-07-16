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
use SystatikoTest\End2End\Asset\Component1\Model\CustomAnnotationClass;
use SystatikoTest\End2End\Asset\Component1\Model\CustomAnnotationMethod;
use SystatikoTest\End2End\Asset\Component1\Model\DependencyInjection;
use SystatikoTest\End2End\Asset\Component1\Model\EventTriggerService;
use SystatikoTest\End2End\Asset\Component1\Model\ExceptionClass;
use SystatikoTest\End2End\Asset\Component1\Model\FacadeInjection;
use SystatikoTest\End2End\Asset\Component1\Model\MethodParameterTest;
use SystatikoTest\End2End\Asset\Component1\Model\NoInjection;
use SystatikoTest\End2End\Asset\Component1\Model\ServiceClass;
use SystatikoTest\End2End\Asset\Generated\Backbone;

class Component1Factory
{

    /**
     * @var Backbone
     */
    private $backbone;

    /**
     * @var Component1Facade
     */
    private $component1Facade;

    /**
     * @var Component1Configuration
     */
    private $component1Configuration;

    /**
     * @var SingletonEntity
     */
    private $singletonEntity;

    /**
     * @var EventTriggerService
     */
    private $eventTriggerService;

    /**
     * @var ExceptionClass
     */
    private $exceptionClass;

    /**
     * @var FacadeInjection
     */
    private $facadeInjection;

    /**
     * @var NoInjection
     */
    private $noInjection;

    /**
     * @var ServiceClass
     */
    private $serviceClass;

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
    public function getConfiguration() : ?Component1Configuration
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
     * 
     * @return CustomAnnotationClass
     */
    public function newCustomAnnotationClass() : CustomAnnotationClass
    {
        return new CustomAnnotationClass();
    }

    /**
     * 
     * @return CustomAnnotationMethod
     */
    public function newCustomAnnotationMethod() : CustomAnnotationMethod
    {
        return new CustomAnnotationMethod();
    }

    /**
     * 
     * @return DependencyInjection
     */
    public function newDependencyInjection() : DependencyInjection
    {
        return new DependencyInjection($this, $this->getConfiguration());
    }

    /**
     * 
     * @return EventTriggerService
     */
    public function getEventTriggerService() : EventTriggerService
    {
        if ($this->eventTriggerService === null) {
            $this->eventTriggerService = new EventTriggerService($this);
        }
        return $this->eventTriggerService;
    }

    /**
     * 
     * @return ExceptionClass
     */
    public function getExceptionClass() : ExceptionClass
    {
        if ($this->exceptionClass === null) {
            $this->exceptionClass = new ExceptionClass();
        }
        return $this->exceptionClass;
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
     * @return MethodParameterTest
     */
    public function newMethodParameterTest() : MethodParameterTest
    {
        return new MethodParameterTest();
    }

    /**
     * @param SampleEntity $entity
     * 
     * @return NoInjection
     */
    public function getNoInjection(SampleEntity $entity) : NoInjection
    {
        if ($this->noInjection === null) {
            $this->noInjection = new NoInjection($entity);
        }
        return $this->noInjection;
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
        $this->backbone->getComponent2Facade()->handleAsyncEvent($event);
    }

    /**
     * @param C1Event $event
     * 
     * @return void
     */
    public function triggerC1Event(C1Event $event)
    {
        $this->backbone->dispatchSynchronousEvent($event);
        $this->backbone->getComponent2Facade()->eventHandler($event);
    }

    /**
     * 
     * @return string
     */
    public function exposeToAllFactories() : string
    {
        return $this->backbone->exposeToAllFactories();
    }

    /**
     * 
     * @return void
     */
    public function resetSingleton()
    {
        $this->component1Facade = null;
        $this->singletonEntity = null;
        $this->eventTriggerService = null;
        $this->exceptionClass = null;
        $this->facadeInjection = null;
        $this->noInjection = null;
        $this->serviceClass = null;
    }
}