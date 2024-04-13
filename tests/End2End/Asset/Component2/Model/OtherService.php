<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component2\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;

#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component2')]
class OtherService
{

    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component2', singleton: true)] // OtherService constructor.
    public function __construct()
    {

    }
}