<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component2\Model;

use SystatikoTest\End2End\Asset\Component1\Event\C1Event;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component2")
 */
class EventHandler
{

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component2", singleton=true)
     * OtherService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @FacadeExposition
     * @EventHandler
     *
     * @param C1Event $event
     *
     * @return void
     */
    public function eventHandler(C1Event $event)
    {

    }

}