<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component2\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Component1\Event\AsyncEvent;
use SystatikoTest\End2End\Asset\Component1\Event\C1Event;

#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component2')]
class EventHandler
{

    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component2', singleton: true)] // OtherService constructor.
    public function __construct()
    {
    }

    /**
     * @EventHandler
     *
     * @param C1Event $event
     * @return void
     */
    #[FacadeExposition]
    #[\Systatiko\Annotation\EventHandler]
    public function eventHandler(C1Event $event)
    {

    }

    /**
     *
     * @param AsyncEvent $event
     * @return void
     */
    #[FacadeExposition]
    #[\Systatiko\Annotation\EventHandler]
    public function handleAsyncEvent(AsyncEvent $event)
    {
        $event->fromPayload([
            "async_handler" => true
        ]);
    }


}