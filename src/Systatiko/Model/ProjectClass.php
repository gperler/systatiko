<?php

namespace Systatiko\Model;

use Systatiko\Annotation\Configuration;
use Systatiko\Annotation\Event;
use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use Systatiko\Reader\PHPClass;
use Systatiko\Reader\PHPMethod;
use Systatiko\Reader\PHPParameter;

class ProjectClass
{

    /**
     * @var Project
     */
    private $project;

    /**
     * @var PHPClass
     */
    private $phpClass;

    /**
     * ProjectClass constructor.
     * @param Project $project
     * @param PHPClass $phpClass
     */
    public function __construct(Project $project, PHPClass $phpClass)
    {
        $this->project = $project;
        $this->phpClass = $phpClass;
    }

    /**
     * @return null|Factory
     */
    public function getComponentFactoryAnnotation()
    {
        $constructor = $this->phpClass->getConstructorMethod();
        if ($constructor === null) {
            return null;
        }
        return $constructor->getMethodAnnotation(Factory::class);
    }

    /**
     * @return null|Event
     */
    public function getEventAnnotation()
    {
        return $this->phpClass->getClassAnnotation(Event::class);
    }

    /**
     * @param string $className
     * @return null|object
     */
    public function getAnnotation(string $className)
    {
        return $this->phpClass->getClassAnnotation($className);
    }

    /**
     * @return PHPMethod[]
     */
    public function getPHPMethodList(): array
    {
        return $this->phpClass->getPHPMethodList();
    }

    /**
     * @return null|FacadeExposition
     */
    public function getComponentFacadeAnnotation()
    {
        new FacadeExposition();
        return $this->phpClass->getClassAnnotation(FacadeExposition::class);
    }

    /**
     * @return null|Configuration
     */
    public function getComponentConfigurationAnnotation()
    {
        return $this->phpClass->getClassAnnotation(Configuration::class);
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->phpClass->getClassName();
    }

    /**
     * @return string
     */
    public function getClassShortName(): string
    {
        return $this->phpClass->getClassShortName();
    }

    /**
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->phpClass->getNamespaceName();
    }

    /**
     * @return string
     */
    public function getMemberName(): string
    {
        return lcfirst($this->getClassShortName());
    }

    /**
     * @return PHPParameter[]
     */
    public function getConstructorParameter()
    {
        $constructor = $this->phpClass->getConstructorMethod();
        if ($constructor === null) {
            return [];
        }
        return $constructor->getMethodParameterList();

    }

    /**
     * @return string
     */
    public function getConstructorInvocationSignature(): string
    {
        $constructor = $this->phpClass->getConstructorMethod();
        if ($constructor === null) {
            return '';
        }
        return $constructor->getInvocationSignature();
    }

    /**
     * @param string $interfaceName
     *
     * @return bool
     */
    public function implementsInterface(string $interfaceName): bool
    {
        return $this->phpClass->implementsInterface($interfaceName);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function isSubclassOf(string $className): bool
    {
        return $this->phpClass->isSubclassOf($className);
    }
}