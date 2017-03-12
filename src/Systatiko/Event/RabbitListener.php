<?php

declare(strict_types = 1);

namespace Systatiko\Event;

use SystatikoTest\End2End\Asset\Generated\Backbone;

class RabbitListener
{


    public function onMessage(array $payload) {

        $backbone = Backbone::getInstance();

        $event = $backbone->newEvent("xyz");
        $event->fromPayload($payload);

        $backbone->dispatchInboundAsynchronousEvent($event);

    }
}