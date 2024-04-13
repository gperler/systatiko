<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Component1\Entity\InjectContext;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;

#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1')]
class ServiceClass
{

    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1', singleton: true)] // ServiceClass constructor.
    public function __construct()
    {
    }

    /**
     *
     *
     * @param SampleEntity $entity
     * @return SampleEntity
     */
    #[FacadeExposition]
    public function hasReturnType(SampleEntity $entity): SampleEntity
    {
        return $entity;
    }

    /**
     *
     * @param SampleEntity $entity
     * @return SampleEntity
     */
    #[FacadeExposition]
    public function hasOptionalReturnType(SampleEntity $entity)
    {
        return null;
    }

    /**
     *
     * @param SampleEntity $entity
     * @return SampleEntity[]
     */
    #[FacadeExposition]
    public function hasArrayReturnType(SampleEntity $entity): array
    {
        return [];
    }

    /**
     *
     * @param SampleEntity $entity
     * @return SampleEntity[]
     */
    #[FacadeExposition]
    public function hasOptionalArrayReturnType(SampleEntity $entity)
    {
        return [];
    }

    /**
     *
     * @param mixed $x
     * @return void
     */
    #[FacadeExposition]
    public function noReturnType($x)
    {
    }

    /**
     *
     * @param mixed $x
     * @return mixed
     */
    #[FacadeExposition]
    public function mixedReturnType($x)
    {
        return 7;
    }

    /**
     * @return InjectContext
     */
    #[FacadeExposition]
    public function getInjectContext(): InjectContext
    {
        return new InjectContext();
    }
}