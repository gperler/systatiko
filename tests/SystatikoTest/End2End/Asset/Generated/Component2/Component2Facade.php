<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component2;

use SystatikoTest\End2End\Asset\Component1\Event\AsyncEvent;
use SystatikoTest\End2End\Asset\Component1\Event\C1Event;

class Component2Facade
{

    /**
     * @var Component2Facade
     */
    protected $factory;

    /**
     * @param Component2Factory $factory
     * 
     */
    public function __construct(Component2Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param C1Event $event
     * 
     * @return void
     */
    public function eventHandler(C1Event $event)
    {
        $this->factory->getEventHandler()->eventHandler($event);
    }

    /**
     * @param AsyncEvent $event
     * 
     * @return void
     */
    public function handleAsyncEvent(AsyncEvent $event)
    {
        $this->factory->getEventHandler()->handleAsyncEvent($event);
    }
}