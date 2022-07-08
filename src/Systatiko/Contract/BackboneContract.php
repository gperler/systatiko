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
     * @param string $componentName
     *
     * @return array|null
     */
    public function getComponentConfiguration(string $componentName): ?array;

    /**
     * @param AsynchronousEventHandler $handler
     *
     * @return void
     */
    public function addOutboundAsynchronousEventHandler(AsynchronousEventHandler $handler): void;

    /**
     * @param AsynchronousEvent $event
     */
    public function dispatchOutboundAsynchronousEvent(AsynchronousEvent $event);

    /**
     * @param SynchronousEvent $event
     */
    public function dispatchSynchronousEvent(SynchronousEvent $event);

    /**
     * @param SynchronousEventHandler $handler
     *
     * @return void
     */
    public function addSynchronousEventHandler(SynchronousEventHandler $handler): void;


    /**
     * @param AsynchronousEvent $event
     *
     * @return void
     */
    public function dispatchInboundAsynchronousEvent(AsynchronousEvent $event): void;

    /**
     * @param string $eventName
     * @param array $payload
     *
     * @return AsynchronousEvent
     *
     * @throws EventNotDefinedException
     */
    public function newAsynchronousEvent(string $eventName, array $payload) : AsynchronousEvent;
}