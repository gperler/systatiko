<?php

declare(strict_types=1);

namespace Systatiko\Model;

use Civis\Common\StringUtil;
use Systatiko\Annotation\Event;
use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Contract\SynchronousEvent;

class ComponentEvent
{

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var ProjectClass
     */
    private $projectClass;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var ComponentEventHandler[]
     */
    private $eventHandlerList;

    /**
     * @var ComponentFactory
     */
    private $responsibleFactory;

    /**
     * ComponentEvent constructor.
     *
     * @param ProjectClass $projectClass
     * @param Event $event
     */
    public function __construct(ProjectClass $projectClass, Event $event)
    {
        $this->projectClass = $projectClass;
        $this->namespace = $event->getNamespace();
        $this->eventName = $event->name;
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
    public function getEventClassName(): string
    {
        return $this->projectClass->getClassName();
    }

    /**
     * @return string
     */
    public function getEventClassShortName(): string
    {
        return StringUtil::getEndAfterLast($this->getEventClassName(), "\\");
    }

    /**
     * @return string
     */
    public function getTriggerMethodName(): string
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

    /**
     * @return bool
     */
    public function isAsynchronousEvent(): bool
    {
        return $this->projectClass->implementsInterface(AsynchronousEvent::class);
    }

    /**
     * @return bool
     */
    public function isSynchronousEvent(): bool
    {
        return $this->projectClass->implementsInterface(SynchronousEvent::class);
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @return ComponentFactory
     */
    public function getResponsibleFactory(): ComponentFactory
    {
        return $this->responsibleFactory;
    }

    /**
     * @param ComponentFactory $responsibleFactory
     */
    public function setResponsibleFactory(ComponentFactory $responsibleFactory)
    {
        $this->responsibleFactory = $responsibleFactory;
    }

}