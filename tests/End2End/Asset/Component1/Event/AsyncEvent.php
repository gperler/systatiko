<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Event;

use Systatiko\Contract\AsynchronousEvent;

class AsyncEvent implements AsynchronousEvent
{
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
