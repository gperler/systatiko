<?php

namespace Systatiko\Contract;

interface SynchronousEventHandler
{

    public function handleEvent(SynchronousEvent $event);

}