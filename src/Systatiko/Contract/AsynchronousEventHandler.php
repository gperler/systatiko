<?php

namespace Systatiko\Contract;

interface AsynchronousEventHandler
{
    public function handleEvent(AsynchronousEvent $event);
}