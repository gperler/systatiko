<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 * @author Gregor Müller
 */
class EventTriggerService
{

    /**
     * @var Component1Factory;
     */
    protected $factory;

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1", singleton=true)
     * EventTriggerService constructor.
     *
     * @param Component1Factory $factory
     */
    public function __construct(Component1Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @FacadeExposition()
     *
     * @param array $payload
     */
    public function triggerAsyncEvent(array $payload)
    {
        $event = $this->factory->newAsyncEvent();
        $event->fromPayload($payload);
        $this->factory->triggerAsyncEvent($event);
    }


    /**
     * @FacadeExposition()
     *
     * @param null|string $test
     */
    public function testNullableParameter(?string $test) {

    }
}