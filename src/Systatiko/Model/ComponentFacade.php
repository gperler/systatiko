<?php

namespace Systatiko\Model;

use Civis\Common\StringUtil;
use Systatiko\Annotation\FacadeExposition;
use Systatiko\Reader\PHPClassName;
use Systatiko\Reader\PHPMethod;

class ComponentFacade
{

    const FACADE_SUFFIX = "Facade";

    /**
     * @var Project
     */
    private $project;

    /**
     * @var ProjectClass
     */
    private $projectClass;

    /**
     * @var ComponentFactory
     */
    private $componentFactory;

    /**
     * @var string
     */
    private $componentFacadeNamespace;

    /**
     * @var string
     */
    private $factoryClassName;

    /**
     * @var ComponentFacadeMethod[]
     */
    private $componentFacadeMethodList;

    /**
     * @var ComponentEventHandler[]
     */
    private $eventHandlerList;


    /**
     * ComponentFacade constructor.
     *
     * @param Project $project
     * @param FacadeExposition $facadeExposition
     * @param ComponentFactory $componentFactory
     */
    public function __construct(Project $project, FacadeExposition $facadeExposition, ComponentFactory $componentFactory)
    {
        $this->project = $project;
        $this->componentFactory = $componentFactory;
        $this->componentFacadeNamespace = $facadeExposition->getNamespace();
        $this->factoryClassName = $facadeExposition->getFactoryClassName();
        $this->componentFacadeMethodList = [];
        $this->eventHandlerList = [];
    }


    /**
     * @param Project $project
     */
    public function update(Project $project)
    {
        foreach ($this->componentFacadeMethodList as $method) {
            $method->update($project);
        }
        foreach ($this->eventHandlerList as $eventHandler) {
            $eventHandler->update($project);
        }
    }


    /**
     * @param $namespace
     *
     * @return bool
     */
    public function isResponsible($namespace): bool
    {
        return $this->componentFacadeNamespace === $namespace;
    }


    /**
     * @param FacadeExposition $exposition
     * @param ProjectClass $projectClass
     */
    public function addFacadeMethodList(FacadeExposition $exposition, ProjectClass $projectClass)
    {
        foreach ($projectClass->getPHPMethodList() as $phpMethod) {
            $this->addFacadeMethod($projectClass, $phpMethod);
        }
    }


    /**
     * @param ProjectClass $projectClass
     * @param PHPMethod $phpMethod
     */
    private function addFacadeMethod(ProjectClass $projectClass, PHPMethod $phpMethod)
    {
        $facadeExposition = $phpMethod->getMethodAnnotation(FacadeExposition::class);

        if ($facadeExposition === null) {
            return;
        }

        $factoryMethod = $this->componentFactory->getFactoryMethodByClassName($projectClass->getClassName());

        // if a class does not have its own @Factory annotation
        // the @FacadeExposition can determine a Factory Method to use
        if ($factoryMethod === null && $this->factoryClassName !== null) {
            $className = new PHPClassName($this->factoryClassName);
            $factoryMethod = $this->componentFactory->getComponentFactoryMethodByClassName($className);
        }

        if ($factoryMethod === null) {
            return;
        }

        $componentFacadeMethod = new ComponentFacadeMethod($projectClass, $factoryMethod, $phpMethod);
        $this->componentFacadeMethodList[] = $componentFacadeMethod;

        $this->handleEventHandler($componentFacadeMethod);
    }


    /**
     * @param ComponentFacadeMethod $method
     */
    private function handleEventHandler(ComponentFacadeMethod $method)
    {
        $handledEvent = $method->getHandledEvent();
        if ($handledEvent === null) {
            return;
        }
        $this->eventHandlerList[] = new ComponentEventHandler($this, $method, $handledEvent);
    }


    /**
     * @return ComponentFacadeMethod[]
     */
    public function getComponentFacadeMethodList()
    {
        return $this->componentFacadeMethodList;
    }


    /**
     * @return string
     */
    public function getClassShortName(): string
    {
        if (strrpos($this->componentFacadeNamespace, "\\") === false) {
            return ucfirst($this->componentFacadeNamespace) . self::FACADE_SUFFIX;
        }
        $className = StringUtil::getEndAfterLast($this->componentFacadeNamespace, "\\");
        return ucfirst($className) . self::FACADE_SUFFIX;
    }


    /**
     * @return string
     */
    public function getMemberName(): string
    {
        return lcfirst($this->getClassShortName());
    }


    /**
     * @return string
     */
    public function getFactoryMethodName(): string
    {
        return "get" . $this->getClassShortName();
    }


    /**
     * @return string
     */
    public function getFactoryClassName(): string
    {
        return $this->componentFactory->getClassName();
    }


    /**
     * @return string
     */
    public function getFactoryMemberName(): string
    {
        return $this->componentFactory->getMemberName();
    }


    /**
     * @return string
     */
    public function getFactoryClassShortName(): string
    {
        return $this->componentFactory->getClassShortName();
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->componentFacadeNamespace . "\\" . $this->getClassShortName();
    }


    /**
     * @param string $facadeClassName
     *
     * @return null|string
     */
    public function getBackboneAccessor(string $facadeClassName)
    {
        if ($facadeClassName === $this->getClassName()) {
            return $this->getFactoryMethodName();
        }
        return null;
    }


    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->getClassShortName() . ".php";
    }


    /**
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->componentFacadeNamespace;
    }

}