<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component2\Model;

use Systatiko\Annotation\FacadeExposition;

/**
 * @FacadeExposition(
 *         namespace="SystatikoTest\End2End\Asset\Generated\Component2",
 *
 * )
 *
 */
class BaseClass
{

    /**
     * @FacadeExposition(factoryClassName="SystatikoTest\End2End\Asset\Component2\Model\SubClass")
     */
    public function useConstructorOfSubClass()
    {
    }

}