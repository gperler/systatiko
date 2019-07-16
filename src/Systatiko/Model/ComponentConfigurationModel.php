<?php

namespace Systatiko\Model;

class ComponentConfigurationModel
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var ProjectClass
     */
    private $projectClass;

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return ProjectClass
     */
    public function getProjectClass(): ProjectClass
    {
        return $this->projectClass;
    }

    /**
     * @param ProjectClass $projectClass
     */
    public function setProjectClass($projectClass)
    {
        $this->projectClass = $projectClass;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->projectClass->getClassName();
    }

}