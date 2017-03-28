<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 */
class ServiceClass
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
     * @CustomAnnotation(roleRequired="myRole")
     *
     * @param SampleEntity $entity
     *
     * @return SampleEntity
     */
    public function hasReturnType(SampleEntity $entity) : SampleEntity
    {
        return $entity;
    }

    /**
     * @FacadeExposition
     *
     * @param SampleEntity $entity
     *
     * @return SampleEntity
     */
    public function hasOptionalReturnType(SampleEntity $entity)
    {
        return null;
    }

    /**
     * @FacadeExposition
     *
     * @param SampleEntity $entity
     *
     * @return SampleEntity[]
     */
    public function hasArrayReturnType(SampleEntity $entity) : array
    {
        return [];
    }

    /**
     * @FacadeExposition
     *
     * @param SampleEntity $entity
     *
     * @return SampleEntity[]
     */
    public function hasOptionalArrayReturnType(SampleEntity $entity)
    {
        return [];
    }

    /**
     * @FacadeExposition
     *
     * @param mixed $x
     *
     * @return void
     */
    public function noReturnType($x)
    {
    }

    /**
     * @FacadeExposition
     *
     * @param mixed $x
     *
     * @return mixed
     */
    public function mixedReturnType($x)
    {
        return 7;
    }
}