<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use Systatiko\Exception\EventNotDefinedException;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 */
class ExceptionClass
{

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1", singleton=true)
     * ServiceClass constructor.
     */
    public function __construct()
    {
    }

    /**
     * @FacadeExposition()
     *
     * @param SampleEntity $entity
     *
     * @return null|SampleEntity
     * @throws EventNotDefinedException
     */
    public function throwsException(SampleEntity $entity): ?SampleEntity
    {
        if ($entity === null) {
            throw new EventNotDefinedException();
        }
        return $entity;
    }


    /**
     * @FacadeExposition()
     * @throws \Exception
     */
    public function throwsDefaultException()
    {
        throw new \Exception();
    }

}