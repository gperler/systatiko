<?php

namespace Systatiko\Model;

use Civis\Common\StringUtil;
use Systatiko\Annotation\Factory;
use Systatiko\Configuration\InjectionConfiguration;
use Systatiko\Reader\PHPClassName;

class ComponentFactory
{

    public const FACTORY_SUFFIX = "Factory";

    /**
     * @var string
     */
    private $componentFactoryNamespace;

    /**
     * @var ComponentFactoryMethod[]
     */
    private $componentFactoryMethodList;

    /**
     * @var ComponentEvent[]
     */
    private $componentEventList;

    /**
     * @var ComponentFacade
     */
    private $componentFacade;

    /**
     * @var ComponentConfigurationModel
     */
    private $componentConfigurationModel;

    /**
     * @var InjectionConfiguration
     */
    private $injectionConfiguration;

    /**
     * ComponentFactory constructor.
     */
    public function __construct(private readonly Project $project, Factory $factory)
    {
        $this->componentFactoryNamespace = $factory->getNamespace();
        $this->componentFactoryMethodList = [];
        $this->componentEventList = [];
        $this->componentFacade = null;
        $this->injectionConfiguration = $this->project->getInjectionConfiguration();
    }

    /**
     * @return bool
     */
    public function isResponsible(string $namespace): bool
    {
        return $this->componentFactoryNamespace === $namespace;
    }

    public function setComponentFacade(ComponentFacade $componentFacade)
    {
        $this->componentFacade = $componentFacade;
    }

    /**
     * @return ComponentFacade
     */
    public function getComponentFacade()
    {
        return $this->componentFacade;
    }

    public function addComponentEvent(ComponentEvent $componentEvent)
    {
        $componentEvent->setResponsibleFactory($this);
        $this->componentEventList[] = $componentEvent;
    }

    public function addFactoryMethod(Factory $factory, ProjectClass $projectClass)
    {
        $this->componentFactoryMethodList[] = new ComponentFactoryMethod($this, $factory, $projectClass);
    }

    public function update(Project $project)
    {
        foreach ($this->componentFactoryMethodList as $componentFactoryClass) {
            $componentFactoryClass->update($project);
        }
    }

    /**
     * @return null|ComponentFactoryMethod
     */
    public function getComponentFactoryMethodByClassName(PHPClassName $className)
    {
        foreach ($this->componentFactoryMethodList as $componentFactoryMethod) {
            if ($componentFactoryMethod->getClassName() === $className->getClassName()) {
                return $componentFactoryMethod;
            }
        }
        return null;
    }

    /**
     * @return ComponentFactoryMethod[]
     */
    public function getComponentFactoryMethodList(): array
    {
        return $this->componentFactoryMethodList;
    }

    /**
     * @return string
     */
    public function getComponentName(): string
    {
        if (strrpos($this->componentFactoryNamespace, "\\") === false) {
            return ucfirst($this->componentFactoryNamespace);
        }
        $componentName = StringUtil::getEndAfterLast($this->componentFactoryNamespace, "\\");
        return ucfirst($componentName);
    }

    /**
     * @return string
     */
    public function getClassShortName(): string
    {
        return $this->getComponentName() . self::FACTORY_SUFFIX;
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
    public function getClassName(): string
    {
        return $this->componentFactoryNamespace . "\\" . $this->getClassShortName();
    }

    /**
     * @return ComponentFactoryMethod|null
     */
    public function getFactoryMethodByClassName(string $className)
    {
        foreach ($this->componentFactoryMethodList as $class) {
            if ($class->getClassName() === $className) {
                return $class;
            }
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
    public function getFilePath(): string
    {
        return str_replace("\\", DIRECTORY_SEPARATOR, $this->componentFactoryNamespace);
    }

    /**
     * @return string
     */
    public function getFileNamePath(): string
    {
        return $this->getFilePath() . "/" . $this->getFileName();
    }

    /**
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->componentFactoryNamespace;
    }

    /**
     * @return string[]
     */
    public function getUsedClassList(): array
    {
        $usedClassList = [];
        foreach ($this->componentFactoryMethodList as $class) {
            $usedClassList = array_merge($usedClassList, $class->getUsedClassList());
        }
        foreach ($this->componentEventList as $componentEvent) {
            $usedClassList[] = $componentEvent->getProjectClass()->getClassName();
        }
        if ($this->componentConfigurationModel !== null) {
            $usedClassList[] = $this->componentConfigurationModel->getClassName();
        }

        return array_unique($usedClassList);
    }

    /**
     * @return ComponentConfigurationModel
     */
    public function getComponentConfigurationModel()
    {
        return $this->componentConfigurationModel;
    }

    /**
     * @param ComponentConfigurationModel $componentConfigurationModel
     */
    public function setComponentConfigurationModel($componentConfigurationModel)
    {
        $this->componentConfigurationModel = $componentConfigurationModel;
    }

    /**
     * @return ProjectClass|null
     */
    public function getComponentConfigurationClass()
    {
        if ($this->componentConfigurationModel === null) {
            return null;
        }
        return $this->componentConfigurationModel->getProjectClass();
    }

    /**
     * @return ComponentEvent[]
     */
    public function getComponentEventList()
    {
        return $this->componentEventList;
    }

    /**
     * @return InjectionConfiguration
     */
    public function getInjectionConfiguration(): InjectionConfiguration
    {
        return $this->injectionConfiguration;
    }

}