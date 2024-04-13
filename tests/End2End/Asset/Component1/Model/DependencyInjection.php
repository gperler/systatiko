<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Component1\Component1Configuration;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory;

#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1')]
class DependencyInjection
{

    /**
     * @var Component1Factory
     */
    protected $factory;

    /**
     * @var Component1Configuration
     */
    protected $configuration;

    /**
     *
     * @param Component1Factory $factory
     * @param Component1Configuration $configuration
     */
    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1', singleton: false)] // ServiceClass constructor.
    public function __construct(Component1Factory $factory, Component1Configuration $configuration)
    {
        $this->factory = $factory;
        $this->configuration = $configuration;
    }

    /**
     * @return bool
     */
    #[FacadeExposition]
    public function getInjectionStatus() : bool
    {
        return $this->factory !== null && $this->configuration !== null;
    }

}