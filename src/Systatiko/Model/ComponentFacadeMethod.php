<?php

namespace Systatiko\Model;

use Systatiko\Annotation\EventHandler;
use Systatiko\Reader\PHPClassName;
use Systatiko\Reader\PHPMethod;
use Systatiko\Reader\PHPMethodReturnType;
use Systatiko\Reader\PHPParameter;

class ComponentFacadeMethod
{

    /**
     * ComponentFacadeMethod constructor.
     */
    public function __construct(private readonly ProjectClass $projectClass, private readonly ComponentFactoryMethod $factoryMethod, private readonly PHPMethod $phpMethod)
    {
    }

    public function update(Project $project)
    {

    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->phpMethod->getMethodName();
    }

    /**
     * @return PHPClassName[]
     */
    public function getThrownExceptionList(): array
    {
        return $this->phpMethod->getThrownExceptionList();
    }

    /**
     * @return string
     */
    public function getInvocationSignature(): string
    {
        $parameterList = [];
        foreach ($this->phpMethod->getMethodParameterList() as $parameter) {
            $parameterList[] = '$' . $parameter->getName();
        }

        return implode(", ", $parameterList);

    }

    /**
     * @return PHPParameter[]
     */
    public function getMethodParameterList()
    {
        return $this->phpMethod->getMethodParameterList();
    }

    /**
     * @return PHPMethodReturnType
     */
    public function getMethodReturnType()
    {
        return $this->phpMethod->getMethodReturnType();
    }

    /**
     * @return string
     */
    public function getFactoryMethodName(): string
    {
        return $this->factoryMethod->getFactoryMethodName();
    }

    /**
     * @return null|object
     */
    public function getHandledEvent()
    {
        $eventHandlerAnnotation = $this->phpMethod->getMethodAnnotation(EventHandler::class);

        if ($eventHandlerAnnotation === null) {
            return null;
        }

        // TODO: make sure it has exactly one param

        $parameterList = $this->phpMethod->getMethodParameterList();
        return $parameterList[0]->getClassName()->getClassName();

    }

    /**
     * @param $className
     *
     * @return mixed|null
     */
    public function getMethodAnnotation($className)
    {
        return $this->phpMethod->getMethodAnnotation($className);
    }

    /**
     * @param $className
     *
     * @return null|object
     */
    public function getClassAnnotation($className)
    {
        return $this->projectClass->getAnnotation($className);
    }

    /**
     * @param $className
     *
     * @return mixed|null|object
     */
    public function getMethodOrClassAnnotation($className)
    {
        $methodAnnotation = $this->getMethodAnnotation($className);
        if ($methodAnnotation !== null) {
            return $methodAnnotation;
        }
        return $this->getClassAnnotation($className);
    }

    /**
     * @return string
     */
    public function getDelegatedClassName(): string
    {
        return $this->projectClass->getClassName();
    }

}