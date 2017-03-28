<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component2\Model;

use SystatikoTest\End2End\Asset\Generated\Backbone;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component2")
 */
class SecurityService
{

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component2", singleton=true)
     * OtherService constructor.
     */
    public function __construct()
    {

    }

    /**
     * @FacadeExposition
     *
     * @param string $roleName
     */
    public function isInRole(string $roleName)
    {
    }

}