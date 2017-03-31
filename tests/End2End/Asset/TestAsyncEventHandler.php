<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset;

use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Contract\AsynchronousEventHandler;

class TestAsyncEventHandler implements AsynchronousEventHandler
{

    /**
     * @var array
     */
    private $payload;

    /**
     * @param AsynchronousEvent $event
     */
    public function handleEvent(AsynchronousEvent $event)
    {
        $this->payload = $event->getPayload();
    }

    /**
     * @return array
     */
    public function getPayload() : array
    {
        return $this->payload;
    }

}