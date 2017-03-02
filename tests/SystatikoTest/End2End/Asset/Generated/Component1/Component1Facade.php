<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component1;

use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;

class Component1Facade
{

    /**
     * @var Component1Facade
     */
    protected $factory;

    /**
     * @param Component1Factory $factory
     * 
     */
    public function __construct(Component1Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param SampleEntity $entity
     * 
     * @return SampleEntity
     */
    public function hasReturnType(SampleEntity $entity) : SampleEntity
    {
        return $this->factory->getServiceClass()->hasReturnType($entity);
    }

    /**
     * @param SampleEntity $entity
     * 
     * @return SampleEntity|null
     */
    public function hasOptionalReturnType(SampleEntity $entity)
    {
        return $this->factory->getServiceClass()->hasOptionalReturnType($entity);
    }

    /**
     * @param SampleEntity $entity
     * 
     * @return SampleEntity[]
     */
    public function hasArrayReturnType(SampleEntity $entity) : array
    {
        return $this->factory->getServiceClass()->hasArrayReturnType($entity);
    }

    /**
     * @param SampleEntity $entity
     * 
     * @return SampleEntity[]|null
     */
    public function hasOptionalArrayReturnType(SampleEntity $entity)
    {
        return $this->factory->getServiceClass()->hasOptionalArrayReturnType($entity);
    }

    /**
     * @param mixed $x
     * 
     * @return void
     */
    public function noReturnType($x)
    {
        $this->factory->getServiceClass()->noReturnType($x);
    }

    /**
     * @param mixed $x
     * 
     * @return mixed
     */
    public function mixedReturnType($x)
    {
        return $this->factory->getServiceClass()->mixedReturnType($x);
    }
}