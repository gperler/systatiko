<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component2;

use SystatikoTest\End2End\Asset\Component2\Entity\BaseEntity;
use SystatikoTest\End2End\Asset\Component2\Entity\OverWriteEntity;
use SystatikoTest\End2End\Asset\Component2\Model\EventHandler;
use SystatikoTest\End2End\Asset\Component2\Model\InjectContextService;
use SystatikoTest\End2End\Asset\Component2\Model\OtherService;
use SystatikoTest\End2End\Asset\Component2\Model\SecurityService;
use SystatikoTest\End2End\Asset\Component2\Model\SubClass;
use SystatikoTest\End2End\Asset\Generated\Backbone;

class Component2Factory
{

    /**
     * @var Backbone|null
     */
    protected ?Backbone $backbone;

    /**
     * @var Component2Facade|null
     */
    protected ?Component2Facade $component2Facade = null;

    /**
     * @var EventHandler|null
     */
    protected ?EventHandler $eventHandler = null;

    /**
     * @var InjectContextService|null
     */
    protected ?InjectContextService $injectContextService = null;

    /**
     * @var OtherService|null
     */
    protected ?OtherService $otherService = null;

    /**
     * @var SecurityService|null
     */
    protected ?SecurityService $securityService = null;

    /**
     * @var SubClass|null
     */
    protected ?SubClass $subClass = null;

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
     * @return Component2Facade
     */
    public function getComponent2Facade(): Component2Facade
    {
        if ($this->component2Facade === null) {
            $this->component2Facade = new Component2Facade(
                $this->backbone,
                $this,
            );
        }
        return $this->component2Facade;
    }


    /**
     * 
     * @return BaseEntity
     */
    public function newBaseEntity(): BaseEntity
    {
        switch ($this->backbone->getContext()) {
            case "X":
                return  new OverWriteEntity();
            default:
                return  new BaseEntity();
        }
    }


    /**
     * 
     * @return OverWriteEntity
     */
    public function newOverWriteEntity(): OverWriteEntity
    {
        return  new OverWriteEntity();
    }


    /**
     * 
     * @return EventHandler
     */
    public function getEventHandler(): EventHandler
    {
        if ($this->eventHandler === null) {
            $this->eventHandler =  new EventHandler();
        }
        return $this->eventHandler;
    }


    /**
     * 
     * @return InjectContextService
     */
    public function getInjectContextService(): InjectContextService
    {
        if ($this->injectContextService === null) {
            $this->injectContextService =  new InjectContextService(
                $this->backbone->getComponent1Facade()->getInjectContext(),
            );
        }
        return $this->injectContextService;
    }


    /**
     * 
     * @return OtherService
     */
    public function getOtherService(): OtherService
    {
        if ($this->otherService === null) {
            $this->otherService =  new OtherService();
        }
        return $this->otherService;
    }


    /**
     * 
     * @return SecurityService
     */
    public function getSecurityService(): SecurityService
    {
        if ($this->securityService === null) {
            $this->securityService =  new SecurityService();
        }
        return $this->securityService;
    }


    /**
     * 
     * @return SubClass
     */
    public function getSubClass(): SubClass
    {
        if ($this->subClass === null) {
            $this->subClass =  new SubClass();
        }
        return $this->subClass;
    }


    /**
     * 
     * @return string
     */
    public function exposeToAllFactories(): string
    {
        return $this->backbone->exposeToAllFactories();
    }


    /**
     * 
     * @return void
     */
    public function resetSingleton(): void
    {
        $this->component2Facade = null;
        $this->eventHandler = null;
        $this->injectContextService = null;
        $this->otherService = null;
        $this->securityService = null;
        $this->subClass = null;
    }

}