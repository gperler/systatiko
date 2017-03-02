<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset;

use Systatiko\Runtime\FacadeLocatorBase;

class FacadeLocatorProject extends FacadeLocatorBase
{

    /**
     * @ExposeInAllFactories
     */
    public function exposeToAllFactories() : string
    {
        return "hello!";
    }
}