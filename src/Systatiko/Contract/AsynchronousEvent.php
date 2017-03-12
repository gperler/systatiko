<?php

namespace Systatiko\Contract;

interface AsynchronousEvent extends Event
{
    /**
     * @return array
     */
    public function getPayload() : array;

    /**
     * @param array $payload
     *
     * @return void
     */
    public function fromPayload(array $payload);

    /**
     * @return string
     */
    public function getConfig() : string;
}