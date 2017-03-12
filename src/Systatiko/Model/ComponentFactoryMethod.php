<?php

namespace Systatiko\Model;

use Systatiko\Annotation\Factory;
use Systatiko\Reader\PHPClassName;
use Systatiko\Reader\PHPParameter;
use Civis\Common\StringUtil;

class ComponentFactoryMethod
{

    const ERROR_UNKNOWN_PARAMETER = "Parameter %s in class %s is not a registered facade. Only ComponentFactory and ComponentFacades can be injected.";

    const ERROR_OVERWRITE_WITHOUT_CONTEXT = "Overwrite of in class %s needs a context";

    const ERROR_OVERWRITE_DOES_NOT_EXTEND = "Class '%s' overwrites class '%s' but does not extend it. Add extend to declaration of class.";

    const WARINING_UNKWOWN_OVERWRITTEN_CLASS = "Overwritten class %s not found in %s. Only classes in same ComponentFactory can be overwritten";

    /**
     * @var ComponentFactory
     */
    protected $componentFactory;

    /**
     * @var Factory
     */
    protected $factoryAnnotation;

    /**
     * @var ProjectClass
     */
    protected $factoryForProjectClass;

    /**
     * @var ComponentFactoryMethod
     */
    protected $overwrittenComponentFactoryClass;

    /**
     * @var ComponentFactoryMethod[]
     */
    protected $overwritingComponentFactoryClassList;

    /**
     * @var string[]
     */
    protected $constructorInvocationSignatureList;

    /**
     * @var PHPParameter[]
     */
    protected $accessorParameterList;

    /**
     * ComponentFactoryMethod constructor.
     *
     * @param ComponentFactory $componentFactory
     * @param Factory $factory
     * @param ProjectClass $projectClass
     */
    public function __construct(ComponentFactory $componentFactory, Factory $factory, ProjectClass $projectClass)
    {
        $this->componentFactory = $componentFactory;
        $this->factoryAnnotation = $factory;
        $this->factoryForProjectClass = $projectClass;
        $this->overwritingComponentFactoryClassList = [];
        $this->constructorInvocationSignatureList = [];
        $this->accessorParameterList = [];
    }

    /**
     * @param Project $project
     */
    public function update(Project $project)
    {
        $this->updateOverwrite($project);
        $this->updateConstructorSignature($project);
        $this->updateParameterClassName($project);

    }

    /**
     * @param Project $project
     */
    protected function updateOverwrite(Project $project)
    {
        $overwrite = $this->getOverwrite();
        if ($overwrite === null) {
            return;
        }
        $overwriteClassName = new PHPClassName($overwrite);
        $overwritenComponentFactoryClass = $this->componentFactory->getComponentFactoryMethodByClassName($overwriteClassName);
        if ($overwritenComponentFactoryClass === null) {

            $warning = sprintf(self::WARINING_UNKWOWN_OVERWRITTEN_CLASS, $overwrite, $this->getClassName());
            $project->logWarning($warning);
            return;
        }
        $this->overwrittenComponentFactoryClass = $overwritenComponentFactoryClass;
        $overwritenComponentFactoryClass->addOverwritingComponentFactoryClass($this);

        $context = $this->getContext();
        if ($context === null) {
            $error = sprintf(self::ERROR_OVERWRITE_WITHOUT_CONTEXT, $this->getClassName());
            $project->logError($error);
        }

        $overwrittenClassName = $overwritenComponentFactoryClass->getClassName();
        if (!$this->factoryForProjectClass->isSubclassOf($overwrittenClassName)) {
            $error = sprintf(self::ERROR_OVERWRITE_DOES_NOT_EXTEND, $this->factoryForProjectClass->getClassName(), $overwrittenClassName);
            $project->logError($error);
        }
    }

    /**
     * @param Project $project
     */
    protected function updateConstructorSignature(Project $project)
    {
        $constructorParameterList = $this->factoryForProjectClass->getConstructorParameter();

        if (sizeof($constructorParameterList) === 0) {
            return;
        }

        foreach ($this->factoryForProjectClass->getConstructorParameter() as $parameter) {
            $this->getParameterAccessor($project, $parameter);
        }

    }

    /**
     * @param Project $project
     */
    protected function updateParameterClassName(Project $project)
    {
        foreach ($this->accessorParameterList as $parameter) {
            if ($parameter->getClassName() !== null || $parameter->getType() === null) {
                continue;
            }

            // classname might not be set because class is in same namespace and therefore not imported
            $possibleName = $this->factoryForProjectClass->getNamespaceName() . "\\" . $parameter->getType();
            if ($project->getPHPClassByName($possibleName) !== null) {
                $parameter->setClassName($possibleName);
            }
        }
    }

    /**
     * @param Project $project
     * @param PHPParameter $parameter
     */
    protected function getParameterAccessor(Project $project, PHPParameter $parameter)
    {

        if (!$this->factoryAnnotation->injectionAllowed($parameter->getName())) {
            $this->constructorInvocationSignatureList[] = '$' . $parameter->getName();
            $this->accessorParameterList[] = $parameter;
            return;
        }

        $parameterClass = $parameter->getClassName();

        if ($parameterClass === null) {
            $this->constructorInvocationSignatureList[] = '$' . $parameter->getName();
            $this->accessorParameterList[] = $parameter;
            return;
        }

        if ($parameterClass->getClassName() === $this->componentFactory->getClassName()) {
            $this->constructorInvocationSignatureList[] = '$this';
            return;
        }

        $componentConfiguration = $this->componentFactory->getComponentConfigurationModel();
        if ($componentConfiguration !== null && $componentConfiguration->getClassName() === $parameterClass->getClassName()) {
            $this->constructorInvocationSignatureList[] = '$this->getConfiguration()';
            return;
        }

        $factoryMethod = $this->componentFactory->getComponentFactoryMethodByClassName($parameterClass);
        if ($factoryMethod !== null) {
            $this->constructorInvocationSignatureList[] = '$this->' . $factoryMethod->getFactoryMethodName() . '()';
            return;
        }

        $accesor = $project->getBackboneAccessor($parameterClass->getClassName());
        if ($accesor !== null) {
            $this->constructorInvocationSignatureList[] = '$this->backbone->' . $accesor . '()';
            return;

        }

        $this->constructorInvocationSignatureList[] = '$' . $parameter->getName();
        $this->accessorParameterList[] = $parameter;
    }

    /**
     * @param ComponentFactoryMethod $overwriting
     */
    protected function addOverwritingComponentFactoryClass(ComponentFactoryMethod $overwriting)
    {
        $this->overwritingComponentFactoryClassList[] = $overwriting;
    }

    /**
     * @return ComponentFactoryMethod[]
     */
    public function getOverwritingComponentFactoryClassList()
    {
        return $this->overwritingComponentFactoryClassList;
    }

    /**
     * @return bool
     */
    public function hasOverwritingComponentFactoryClassList() : bool
    {
        return sizeof($this->overwritingComponentFactoryClassList) !== 0;
    }

    /**
     * @return string|null
     */
    public function getOverwrite()
    {
        return StringUtil::trimToNull($this->factoryAnnotation->overwrites);
    }

    /**
     * @return string
     */
    public function getContext() : string
    {
        return $this->factoryAnnotation->getContext();
    }

    /**
     * @return string
     */
    public function getClassName() : string
    {
        return $this->factoryForProjectClass->getClassName();
    }

    /**
     * @return string
     */
    public function getClassShortName() : string
    {
        return $this->factoryForProjectClass->getClassShortName();
    }

    /**
     * @return string
     */
    public function getFactoryMethodReturnType() : string
    {
        $factoryReturnType = $this->factoryAnnotation->returnType;
        if ($factoryReturnType !== null) {
            return $factoryReturnType;
        }
        return $this->getClassName();
    }

    /**
     * @return string
     */
    public function getFactoryMethodName() : string
    {
        $prefix = $this->isSingleton() ? "get" : "new";
        return $prefix . $this->getClassShortName();
    }

    /**
     * @return bool
     */
    public function isSingleton() : bool
    {
        return $this->factoryAnnotation->isSingleton();
    }

    /**
     * @return string
     */
    public function getMemberName() : string
    {
        return lcfirst($this->factoryForProjectClass->getClassShortName());
    }

    /**
     * @return string
     */
    public function getMemberType() : string
    {
        return $this->factoryForProjectClass->getClassShortName();
    }

    /**
     * @return string
     */
    public function getConstructorInvocationSignature() : string
    {
        return implode(", ", $this->constructorInvocationSignatureList);
    }

    /**
     * @return string
     */
    public function getAccessorSignature() : string
    {
        $parameterList = [];
        foreach ($this->accessorParameterList as $parameter) {
            $parameterList[] = $parameter->getSignatureSnippet();
        }
        return implode(", ", $parameterList);
    }

    /**
     * @return PHPParameter[]
     */
    public function getAccessorParameterList() : array
    {
        return $this->accessorParameterList;
    }

    /**
     * @return string[]
     */
    public function getUsedClassList() : array
    {
        $useStatementList = [$this->factoryForProjectClass->getClassName()];

        if ($this->factoryAnnotation->returnType !== null) {
            $useStatementList[] = $this->factoryAnnotation->returnType;
        }

        foreach ($this->accessorParameterList as $parameter) {
            if ($parameter->getClassName() !== null) {
                $useStatementList[] = $parameter->getClassName();
            }
        }

        return $useStatementList;
    }

}