<?php

namespace Systatiko\Contract;

use Systatiko\Exception\EventNotDefinedException;

interface BackboneContract
{

    /**
     * @return string|null
     */
    public function getContext(): ?string;

    /**
     * @param string $context
     *
     * @return void
     */
    public function setContext($context): void;

    /**
     * @return array|null
     */
    public function getComponentConfiguration(string $componentName): ?array;

    /**
     * @return void
     */
    public function addOutboundAsynchronousEventHandler(AsynchronousEventHandler $handler): void;

    public function dispatchOutboundAsynchronousEvent(AsynchronousEvent $event);

    public function dispatchSynchronousEvent(SynchronousEvent $event);

    /**
     * @return void
     */
    public function addSynchronousEventHandler(SynchronousEventHandler $handler): void;


    /**
     * @return void
     */
    public function dispatchInboundAsynchronousEvent(AsynchronousEvent $event): void;

    /**
     *
     * @return AsynchronousEvent
     *
     * @throws EventNotDefinedException
     */
    public function newAsynchronousEvent(string $eventName, array $payload) : AsynchronousEvent;
}