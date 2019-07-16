<?php

declare(strict_types = 1);

namespace Systatiko\Model;

class ComponentEventHandler
{

    /**
     * @var string
     */
    private $eventClassName;

    /**
     * @var ComponentFacade
     */
    private $facade;

    /**
     * @var ComponentFacadeMethod
     */
    private $facadeMethod;

    /**
     * ComponentEventHandler constructor.
     *
     * @param ComponentFacade $facade
     * @param ComponentFacadeMethod $method
     * @param string $eventClassName
     */
    public function __construct(ComponentFacade $facade, ComponentFacadeMethod $method, string $eventClassName)
    {
        $this->facade = $facade;
        $this->facadeMethod = $method;
        $this->eventClassName = $eventClassName;
    }

    /**
     * @param Project $project
     */
    public function update(Project $project)
    {
        $event = $project->getComponentEventByName($this->eventClassName);
        if ($event === null) {
            // TODO log error
            return;
        }

        $event->addEventHandler($this);
    }

    /**
     * @return string
     */
    public function getFacadeAccessMethod() : string
    {
        return $this->facade->getFactoryMethodName();
    }

    /**
     * @return string
     */
    public function getFacadeMethodName() : string
    {
        return $this->facadeMethod->getMethodName();
    }
}