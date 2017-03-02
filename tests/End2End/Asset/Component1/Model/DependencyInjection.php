<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use SystatikoTest\End2End\Asset\Component1\Component1Configuration;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 */
class DependencyInjection
{

    /**
     * @Factory(
     *     namespace="SystatikoTest\End2End\Asset\Generated\Component1",
     *     singleton=false,
     *     noInjection="entity"
     * )
     * ServiceClass constructor.
     */
    public function __construct(Component1Factory $factory, Component1Configuration $configuration, SampleEntity $entity)
    {
    }
}