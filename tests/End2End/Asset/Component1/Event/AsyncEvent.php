<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Event;

use Systatiko\Contract\AsynchronousEvent;

/**
 * @Event(namespace="SystatikoTest\End2End\Asset\Generated\Component1", name="com.test.myevent.event1")
 */
class AsyncEvent implements AsynchronousEvent
{

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
     * AsyncEvent constructor.
     */
    public function __construct()
    {

    }

    public function getPayload() : array
    {
        // TODO: Implement getPayload() method.
    }

    public function getConfig() : string
    {
        // TODO: Implement getConfig() method.
    }

    public function fromPayload(array $payload)
    {
        // TODO: Implement fromPayload() method.
    }

}
