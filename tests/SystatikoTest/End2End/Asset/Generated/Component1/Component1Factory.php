<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component1;

use SystatikoTest\End2End\Asset\Component1\Component1Configuration;
use SystatikoTest\End2End\Asset\Component1\Contract\SampleInterface;
use SystatikoTest\End2End\Asset\Component1\Entity\DifferentReturnEntity;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;
use SystatikoTest\End2End\Asset\Component1\Entity\SingletonEntity;
use SystatikoTest\End2End\Asset\Component1\Event\C1Event;
use SystatikoTest\End2End\Asset\Component1\Model\DependencyInjection;
use SystatikoTest\End2End\Asset\Component1\Model\FacadeInjection;
use SystatikoTest\End2End\Asset\Component1\Model\ServiceClass;
use SystatikoTest\End2End\Asset\Generated\FacadeLocator;

class Component1Factory
{

    /**
     * @var FacadeLocator
     */
    protected $locator;

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
     * @param FacadeLocator $locator
     * 
     */
    public function __construct(FacadeLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * 
     * @return Component1Configuration|null
     */
    public function getConfiguration()
    {
        if ($this->component1Configuration === null) {
            $this->component1Configuration = new Component1Configuration();
            $this->component1Configuration->setValueList($this->locator->getComponentConfiguration("Component1"));
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
            $this->component1Facade = new Component1Facade($this);
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
     * 
     * @return SingletonEntity
     */
    public function getSingletonEntity() : SingletonEntity
    {
        if ($this->singletonEntity === null) {
            $this->singletonEntity = new SingletonEntity();
        }
        return $this->singletonEntity;
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
            $this->facadeInjection = new FacadeInjection($this->locator->getComponent2Facade());
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
     * @param C1Event $event
     * 
     * @return void
     */
    public function triggerC1Event(C1Event $event)
    {
        $this->locator->getComponent2Facade()->eventHandler($event);
    }

    /**
     * 
     * @return string
     */
    public function exposeToAllFactories() : string
    {
        return $this->locator->exposeToAllFactories();
    }
}