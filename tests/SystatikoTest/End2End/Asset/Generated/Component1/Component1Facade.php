<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated\Component1;

use Civis\Common\File as Test;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;
use SystatikoTest\End2End\Asset\Generated\Backbone;
use Systatiko\Exception\EventNotDefinedException;

class Component1Facade
{

    /**
     * @var Backbone
     */
    protected $backbone;

    /**
     * @var Component1Factory
     */
    protected $factory;

    /**
     * @param Backbone $backbone
     * @param Component1Factory $factory
     * 
     */
    public function __construct(Backbone $backbone, Component1Factory $factory)
    {
        $this->backbone = $backbone;
        $this->factory = $factory;
    }

    /**
     * 
     * @return void
     */
    public function testMethod1()
    {
        $this->backbone->getComponent2Facade()->isInRole("myRole");
        $this->factory->newCustomAnnotationClass()->testMethod1();
    }

    /**
     * 
     * @return void
     */
    public function testMethod2()
    {
        $this->backbone->getComponent2Facade()->isInRole("myRole");
        $this->factory->newCustomAnnotationClass()->testMethod2();
    }

    /**
     * 
     * @return void
     */
    public function methodWithCustomExtension()
    {
        $this->backbone->getComponent2Facade()->isInRole("myRole");
        $this->factory->newCustomAnnotationMethod()->methodWithCustomExtension();
    }

    /**
     * 
     * @return void
     */
    public function methodWithoutCustomExtension()
    {
        $this->factory->newCustomAnnotationMethod()->methodWithoutCustomExtension();
    }

    /**
     * 
     * @return bool
     */
    public function getInjectionStatus() : bool
    {
        return $this->factory->newDependencyInjection()->getInjectionStatus();
    }

    /**
     * @param array $payload
     * 
     * @return void
     */
    public function triggerAsyncEvent(array $payload)
    {
        $this->factory->getEventTriggerService()->triggerAsyncEvent($payload);
    }

    /**
     * @param string|null $test
     * 
     * @return void
     */
    public function testNullableParameter(?string $test)
    {
        $this->factory->getEventTriggerService()->testNullableParameter($test);
    }

    /**
     * @param SampleEntity $entity
     * 
     * @return SampleEntity|null
     * @throws EventNotDefinedException
     */
    public function throwsException(SampleEntity $entity) : ?SampleEntity
    {
        return $this->factory->getExceptionClass()->throwsException($entity);
    }

    /**
     * 
     * @return void
     * @throws \Exception
     */
    public function throwsDefaultException()
    {
        $this->factory->getExceptionClass()->throwsDefaultException();
    }

    /**
     * 
     * @return bool
     */
    public function getFacadeInjectionStatus() : bool
    {
        return $this->factory->getFacadeInjection()->getFacadeInjectionStatus();
    }

    /**
     * @param Test|null $file
     * @param string[] $array
     * @param mixed|null $mixed
     * 
     * @return Test[]|null
     */
    public function methodReaderTestMe(Test $file = null, array $array, $mixed) : ?array
    {
        return $this->factory->newMethodParameterTest()->methodReaderTestMe($file, $array, $mixed);
    }

    /**
     * @param string $test
     * @param array|null $array
     * @param array $typeList
     * 
     * @return array
     */
    public function methodReaderTestMeToo(string $test = "null", array $array = null, array $typeList) : array
    {
        return $this->factory->newMethodParameterTest()->methodReaderTestMeToo($test, $array, $typeList);
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
    public function hasOptionalReturnType(SampleEntity $entity) : ?SampleEntity
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
    public function hasOptionalArrayReturnType(SampleEntity $entity) : ?array
    {
        return $this->factory->getServiceClass()->hasOptionalArrayReturnType($entity);
    }

    /**
     * @param mixed|null $x
     * 
     * @return void
     */
    public function noReturnType($x)
    {
        $this->factory->getServiceClass()->noReturnType($x);
    }

    /**
     * @param mixed|null $x
     * 
     * @return mixed
     */
    public function mixedReturnType($x)
    {
        return $this->factory->getServiceClass()->mixedReturnType($x);
    }
}