<?php

declare(strict_types = 1);

namespace Systatiko\Model;

class ComponentEventHandler
{

    /**
     * ComponentEventHandler constructor.
     */
    public function __construct(private readonly ComponentFacade $facade, private readonly ComponentFacadeMethod $facadeMethod, private readonly string $eventClassName)
    {
    }

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