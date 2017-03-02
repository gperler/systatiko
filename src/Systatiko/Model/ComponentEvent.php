<?php

declare(strict_types = 1);

namespace Systatiko\Model;


use Civis\Common\StringUtil;

class ComponentEvent
{

    /**
     * @var ProjectClass
     */
    protected $projectClass;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var ComponentEventHandler[]
     */
    protected $eventHandlerList;

    /**
     * ComponentEvent constructor.
     *
     * @param ProjectClass $projectClass
     * @param string $namespace
     */
    public function __construct(ProjectClass $projectClass, string $namespace)
    {
        $this->projectClass = $projectClass;
        $this->namespace = $namespace;
        $this->eventHandlerList = [];
    }

    /**
     * @return ProjectClass
     */
    public function getProjectClass(): ProjectClass
    {
        return $this->projectClass;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getEventClassName() : string
    {
        return $this->projectClass->getClassName();
    }

    /**
     * @return string
     */
    public function getEventClassShortName() : string
    {
        return StringUtil::getEndAfterLast($this->getEventClassName(), "\\");
    }

    /**
     * @return string
     */
    public function getTriggerMethodName() : string
    {
        return "trigger" . ucfirst($this->getEventClassShortName());
    }

    /**
     * @param ComponentEventHandler $componentEventHandler
     */
    public function addEventHandler(ComponentEventHandler $componentEventHandler)
    {
        $this->eventHandlerList[] = $componentEventHandler;
    }

    /**
     * @return ComponentEventHandler[]
     */
    public function getEventHandlerList()
    {
        return $this->eventHandlerList;
    }

}