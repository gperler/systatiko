<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 */
class VoidClass
{

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1", singleton=true)
     * ServiceClass constructor.
     */
    public function __construct()
    {
    }


    /**
     * @FacadeExposition
     *
     *
     * @param SampleEntity $entity
     *
     * @return void
     */
    public function hasVoidReturn(SampleEntity $entity): void
    {
    }


    /**
     * @FacadeExposition
     *
     *
     * @param SampleEntity $entity
     *
     * @return void
     */
    public function hasVoidReturn1(SampleEntity $entity)
    {
    }


    /**
     * @FacadeExposition
     *
     *
     * @param SampleEntity $entity
     *
     */
    public function hasVoidReturn2(SampleEntity $entity): void
    {
    }


    /**
     * @FacadeExposition
     *
     *
     * @param SampleEntity $entity
     *
     */
    public function hasVoidReturn3(SampleEntity $entity)
    {
    }
}