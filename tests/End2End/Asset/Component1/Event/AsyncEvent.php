<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Event;

use Systatiko\Annotation\Event;
use Systatiko\Annotation\Factory;
use Systatiko\Contract\AsynchronousEvent;

/**
 *
 */
#[Event(namespace: "SystatikoTest\End2End\Asset\Generated\Component1", name: "com.test.myevent.event1")]
class AsyncEvent implements AsynchronousEvent
{

    /**
     * @var array
     */
    protected $payload;

    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1')] // AsyncEvent constructor.
    public function __construct()
    {
        $this->payload = [];
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getConfig(): string
    {
        return 's';
    }

    public function fromPayload(array $payload)
    {
        $this->payload = $payload;
    }


}
