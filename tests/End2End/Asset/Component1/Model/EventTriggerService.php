<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory;

/**
 * @author Gregor Müller
 */
#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1')]
class EventTriggerService
{

    /**
     * @var Component1Factory;
     */
    protected $factory;

    /**
     * @param Component1Factory $factory
     */
    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1', singleton: true)] // EventTriggerService constructor.
    public function __construct(Component1Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $payload
     */
    #[FacadeExposition]
    public function triggerAsyncEvent(array $payload)
    {
        $event = $this->factory->newAsyncEvent();
        $event->fromPayload($payload);
        $this->factory->triggerAsyncEvent($event);
    }


    /**
     * @param null|string $test
     */
    #[FacadeExposition]
    public function testNullableParameter(?string $test) {

    }
}