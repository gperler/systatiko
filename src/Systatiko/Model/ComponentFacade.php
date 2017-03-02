<?php

namespace Systatiko\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Reader\PHPMethod;
use Civis\Common\StringUtil;

class ComponentFacade
{

    const FACADE_SUFFIX = "Facade";

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var ProjectClass
     */
    protected $projectClass;

    /**
     * @var ComponentFactory
     */
    protected $componentFactory;

    /**
     * @var string
     */
    protected $componentFacadeNamespace;

    /**
     * @var ComponentFacadeMethod[]
     */
    protected $componentFacadeMethodList;

    /**
     * @var ComponentEventHandler[]
     */
    protected $eventHandlerList;

    public function __construct(Project $project, FacadeExposition $facadeExposition, ComponentFactory $componentFactory)
    {
        $this->project = $project;
        $this->componentFactory = $componentFactory;
        $this->componentFacadeNamespace = $facadeExposition->getNamespace();
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
    public function isResponsible($namespace) : bool
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
    protected function addFacadeMethod(ProjectClass $projectClass, PHPMethod $phpMethod)
    {
        $facadeExposition = $phpMethod->getMethodAnnotation(ProjectClass::FACADE_ANNOTATION_NAME);

        if ($facadeExposition === null) {
            return;
        }

        $factoryMethod = $this->componentFactory->getFactoryMethodByClassName($projectClass->getClassName());
        if ($factoryMethod === null) {
            // TODO : ERROR
        }

        $componentFacadeMethod = new ComponentFacadeMethod($projectClass, $factoryMethod, $phpMethod);
        $this->componentFacadeMethodList[] = $componentFacadeMethod;

        $this->handleEventHandler($componentFacadeMethod);
    }

    /**
     * @param ComponentFacadeMethod $method
     */
    protected function handleEventHandler(ComponentFacadeMethod $method)
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
    public function getClassShortName() : string
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
    public function getMemberName() : string
    {
        return lcfirst($this->getClassShortName());
    }

    /**
     * @return string
     */
    public function getFactoryMethodName() : string
    {
        return "get" . $this->getClassShortName();
    }

    /**
     * @return string
     */
    public function getFactoryClassName() : string
    {
        return $this->componentFactory->getClassName();
    }

    /**
     * @return string
     */
    public function getFactoryMemberName() : string
    {
        return $this->componentFactory->getMemberName();
    }

    /**
     * @return string
     */
    public function getFactoryClassShortName() : string
    {
        return $this->componentFactory->getClassShortName();
    }

    /**
     * @return string
     */
    public function getClassName() : string
    {
        return $this->componentFacadeNamespace . "\\" . $this->getClassShortName();
    }

    /**
     * @param string $facadeClassName
     *
     * @return null|string
     */
    public function getLocatorAccessor(string $facadeClassName)
    {
        if ($facadeClassName === $this->getClassName()) {
            return $this->getFactoryMethodName();
        }
        return null;

    }

    /**
     * @return string
     */
    public function getFileName() : string
    {
        return $this->getClassShortName() . ".php";
    }

    /**
     * @return string
     */
    public function getNamespaceName() : string
    {
        return $this->componentFacadeNamespace;
    }

}