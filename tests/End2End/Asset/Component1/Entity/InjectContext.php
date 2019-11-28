<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Entity;

class InjectContext
{
    const HELLO_MESSAGE = 'Hello';

    /**
     * @return string
     */
    public function sayHello()
    {
        return self::HELLO_MESSAGE;
    }
}