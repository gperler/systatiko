<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Entity;

use Systatiko\Annotation\Factory;

class SingletonEntity
{

    public $id;

    
    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1', singleton: true)]
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}