<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset;

use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Contract\Event;
use Systatiko\Runtime\BackboneBase;
use SystatikoTest\End2End\Asset\Component1\Event\AsyncEvent;

class BackboneProject extends BackboneBase
{

    /**
     * @ExposeInAllFactories
     */
    public function exposeToAllFactories() : string
    {
        return "hello!";
    }

    public function dispatchInboundAsynchronousEvent(AsynchronousEvent $event)
    {
        // TODO: Implement dispatchInboundAsynchronousEvent() method.
    }

    public function newEvent(string $name) : AsynchronousEvent
    {
        // TODO: Implement newEvent() method.
    }

}