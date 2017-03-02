<?php

namespace Systatiko\Model;

use Systatiko\Reader\PHPMethod;
use Systatiko\Reader\PHPMethodReturnType;
use Systatiko\Reader\PHPParameter;

class ComponentFacadeMethod
{

    const EVENT_HANDLER_ANNOTATION_NAME = 'Systatiko\Annotation\EventHandler';

    /**
     * @var ProjectClass
     */
    protected $projectClass;

    /**
     * @var ComponentFactoryMethod
     */
    protected $factoryMethod;

    /**
     * @var PHPMethod
     */
    protected $phpMethod;

    /**
     * ComponentFacadeMethod constructor.
     *
     * @param ProjectClass $projectClass
     * @param ComponentFactoryMethod $factoryMethod
     * @param PHPMethod $phpMethod
     */
    public function __construct(ProjectClass $projectClass, ComponentFactoryMethod $factoryMethod, PHPMethod $phpMethod)
    {
        $this->projectClass = $projectClass;
        $this->factoryMethod = $factoryMethod;
        $this->phpMethod = $phpMethod;
    }

    /**
     * @param Project $project
     */
    public function update(Project $project)
    {

    }

    /**
     * @return string
     */
    public function getMethodLine() : string
    {
        return $this->phpMethod->getMethodDefinition();
    }

    /**
     * @return string
     */
    public function getMethodName() : string
    {
        return $this->phpMethod->getMethodName();
    }

    /**
     * @return string
     */
    public function getInvocationSignature() : string
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
    public function getFactoryMethodName()
    {
        return $this->factoryMethod->getFactoryMethodName();
    }

    /**
     * @return null|object
     */
    public function getHandledEvent()
    {
        $eventHandlerAnnotation = $this->phpMethod->getMethodAnnotation(self::EVENT_HANDLER_ANNOTATION_NAME);

        if ($eventHandlerAnnotation === null) {
            return null;
        }

        // TODO: make sure it has exactly one param

        $parameterList = $this->phpMethod->getMethodParameterList();
        return $parameterList[0]->getClassName()->getClassName();

    }

}