<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component2;

use SystatikoTest\End2End\Asset\Component2\Entity\BaseEntity;
use SystatikoTest\End2End\Asset\Component2\Entity\OverWriteEntity;
use SystatikoTest\End2End\Asset\Component2\Model\EventHandler;
use SystatikoTest\End2End\Asset\Component2\Model\OtherService;
use SystatikoTest\End2End\Asset\Generated\FacadeLocator;

class Component2Factory
{

    /**
     * @var FacadeLocator
     */
    protected $locator;

    /**
     * @var string
     */
    protected $context;

    /**
     * @var Component2Facade
     */
    protected $component2Facade;

    /**
     * @var EventHandler
     */
    protected $eventHandler;

    /**
     * @var OtherService
     */
    protected $otherService;

    /**
     * @param FacadeLocator $locator
     * 
     */
    public function __construct(FacadeLocator $locator)
    {
        $this->locator = $locator;
        $this->context = $locator->getContext();
    }

    /**
     * 
     * @return Component2Facade
     */
    public function getComponent2Facade() : Component2Facade
    {
        if ($this->component2Facade === null) {
            $this->component2Facade = new Component2Facade($this);
        }
        return $this->component2Facade;
    }

    /**
     * 
     * @return BaseEntity
     */
    public function newBaseEntity() : BaseEntity
    {
        switch ($this->context) {
            case "X":
                return new OverWriteEntity();
            default:
                return new BaseEntity();
        }
    }

    /**
     * 
     * @return OverWriteEntity
     */
    public function newOverWriteEntity() : OverWriteEntity
    {
        return new OverWriteEntity();
    }

    /**
     * 
     * @return EventHandler
     */
    public function getEventHandler() : EventHandler
    {
        if ($this->eventHandler === null) {
            $this->eventHandler = new EventHandler();
        }
        return $this->eventHandler;
    }

    /**
     * 
     * @return OtherService
     */
    public function getOtherService() : OtherService
    {
        if ($this->otherService === null) {
            $this->otherService = new OtherService();
        }
        return $this->otherService;
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