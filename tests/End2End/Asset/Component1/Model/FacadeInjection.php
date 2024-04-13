<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Generated\Component2\Component2Facade;

/**
 * @author Gregor Müller
 */
#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1')]
class FacadeInjection
{

    protected $facade;

    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1', singleton: true)] // ServiceClass constructor.
    public function __construct(Component2Facade $facade)
    {
        $this->facade = $facade;
    }

    /**
     * @return bool
     */
    #[FacadeExposition]
    public function getFacadeInjectionStatus(): bool
    {
        return $this->facade !== null;
    }
}