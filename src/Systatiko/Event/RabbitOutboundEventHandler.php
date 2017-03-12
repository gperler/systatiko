<?php

declare(strict_types = 1);

namespace Systatiko\Event;

use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Contract\AsynchronousEventHandler;

class RabbitOutboundEventHandler implements AsynchronousEventHandler
{
    /**
     * @param AsynchronousEvent $event
     */
    public function handleEvent(AsynchronousEvent $event)
    {
        $config = $event->getConfig();

        $payload = $event->getPayload();

        // send to wire
    }

}