<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset;

use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Runtime\BackboneBase;

abstract class BackboneProject extends BackboneBase
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

}