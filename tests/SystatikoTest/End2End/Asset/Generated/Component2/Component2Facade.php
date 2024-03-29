<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component2;

use SystatikoTest\End2End\Asset\Component1\Event\AsyncEvent;
use SystatikoTest\End2End\Asset\Component1\Event\C1Event;
use SystatikoTest\End2End\Asset\Generated\Backbone;

class Component2Facade
{

    /**
     * @var Backbone|null
     */
    protected ?Backbone $backbone;

    /**
     * @var Component2Factory|null
     */
    protected ?Component2Factory $factory;

    /**
     * @param Backbone $backbone
     * @param Component2Factory $factory
     * 
     */
    public function __construct(Backbone $backbone, Component2Factory $factory)
    {
        $this->backbone = $backbone;
        $this->factory = $factory;
    }

    /**
     * 
     * @return void
     */
    public function useConstructorOfSubClass(): void
    {
        $this->factory->getSubClass()->useConstructorOfSubClass();
    }


    /**
     * @param C1Event $event
     * 
     * @return void
     */
    public function eventHandler(C1Event $event): void
    {
        $this->factory->getEventHandler()->eventHandler($event);
    }


    /**
     * @param AsyncEvent $event
     * 
     * @return void
     */
    public function handleAsyncEvent(AsyncEvent $event): void
    {
        $this->factory->getEventHandler()->handleAsyncEvent($event);
    }


    /**
     * 
     * @return string
     */
    public function injectedSayHello(): string
    {
        return $this->factory->getInjectContextService()->injectedSayHello();
    }


    /**
     * @param string $roleName
     * 
     * @return void
     */
    public function isInRole(string $roleName): void
    {
        $this->factory->getSecurityService()->isInRole($roleName);
    }

}