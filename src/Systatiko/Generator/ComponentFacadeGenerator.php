<?php

namespace Systatiko\Generator;

use Nitria\ClassGenerator;
use Nitria\Method;
use Systatiko\Configuration\GeneratorConfiguration;
use Systatiko\Model\ComponentFacade;
use Systatiko\Model\ComponentFacadeMethod;
use Systatiko\Model\Project;

class ComponentFacadeGenerator
{

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var ComponentFacade
     */
    protected $componentFacade;

    /**
     * @var ClassGenerator
     */
    protected $classGenerator;

    /**
     * @var GeneratorConfiguration
     */
    protected $configuration;

    /**
     * ComponentFacadeGenerator constructor.
     *
     * @param Project $project
     * @param ComponentFacade $componentFacade
     */
    public function __construct(Project $project, ComponentFacade $componentFacade)
    {
        $this->project = $project;
        $this->componentFacade = $componentFacade;
    }

    /**
     * @param GeneratorConfiguration $configuration
     */
    public function generate(GeneratorConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->classGenerator = new ClassGenerator($this->componentFacade->getClassName());

        $this->addMember();

        $this->addConstructor();

        $this->addFacadeMethodList();

        $this->classGenerator->writeToPSR0($configuration->getTargetDir());
    }

    /**
     *
     */
    protected function addMember()
    {
        $this->classGenerator->addProtectedProperty("backbone", $this->configuration->getBackboneClassName());
        $this->classGenerator->addProtectedProperty("factory", $this->componentFacade->getFactoryClassName());
    }

    /**
     *
     */
    protected function addConstructor()
    {
        $constructor = $this->classGenerator->addMethod("__construct");
        $constructor->addParameter($this->configuration->getBackboneClassName(), 'backbone');
        $constructor->addParameter($this->componentFacade->getFactoryClassName(), 'factory');
        $constructor->addCodeLine('$this->backbone = $backbone;');
        $constructor->addCodeLine('$this->factory = $factory;');
    }

    /**
     *
     */
    protected function addFacadeMethodList()
    {
        foreach ($this->componentFacade->getComponentFacadeMethodList() as $method) {
            $this->addFacadeMethod($method);
        }
    }

    /**
     * @param ComponentFacadeMethod $facadeMethod
     */
    protected function addFacadeMethod(ComponentFacadeMethod $facadeMethod)
    {
        $methodName = $facadeMethod->getMethodName();
        $invocationSignature = $facadeMethod->getInvocationSignature();

        $method = $this->classGenerator->addMethod($methodName);

        foreach ($facadeMethod->getMethodParameterList() as $parameter) {
            if ($parameter->isAsClassName()) {
                $className = $parameter->getClassName();
                $this->classGenerator->addUsedClassName($className->getClassName(), $className->getAs());
            }
            $fqn = $parameter->getFullyQualifiedName();
            $method->addParameter($fqn, $parameter->getName(), $parameter->getNitriaDefault(), null, $parameter->isAllowsNull());
        }

        foreach($facadeMethod->getThrownExceptionList() as $exception) {
            $method->addException($exception->getClassName());
        }

        // return type
        $methodReturnType = $facadeMethod->getMethodReturnType();

        $optional = $methodReturnType->canBeNull();
        $docBlockReturnType = $methodReturnType->getFullyQualifiedName();

        if ($docBlockReturnType !== null) {
            $method->setReturnType($docBlockReturnType, $optional);
        }

        if ($docBlockReturnType === 'mixed') {
            $method->setReturnType(null, false);
        }

        $this->addBeforeDelegation($facadeMethod, $method, $methodName);

        $return = $method->hasReturnType() || $docBlockReturnType === 'mixed' ? 'return ' : '';
        $method->addCodeLine($return . '$this->factory->' . $facadeMethod->getFactoryMethodName() . "()->$methodName($invocationSignature);");

        $this->addAfterDelegation($facadeMethod, $method, $methodName);

    }

    /**
     * @param ComponentFacadeMethod $facadeMethod
     * @param Method $method
     * @param string $methodName
     */
    protected function addBeforeDelegation(ComponentFacadeMethod $facadeMethod, Method $method, string $methodName)
    {
        foreach ($this->configuration->getFacadeGeneratorExtension() as $extension) {
            $annotation = $facadeMethod->getMethodOrClassAnnotation($extension->getAnnotationClassName());
            if ($annotation === null) {
                continue;
            }
            $extension->beforeDelegation($method, $annotation, $this->componentFacade->getClassName(), $facadeMethod->getDelegatedClassName(), $methodName, $facadeMethod->getMethodParameterList());
        }
    }

    /**
     * @param ComponentFacadeMethod $facadeMethod
     * @param Method $method
     * @param string $methodName
     */
    protected function addAfterDelegation(ComponentFacadeMethod $facadeMethod, Method $method, string $methodName)
    {
        foreach ($this->configuration->getFacadeGeneratorExtension() as $extension) {
            $annotation = $facadeMethod->getMethodOrClassAnnotation($extension->getAnnotationClassName());
            if ($annotation === null) {
                continue;
            }
            $extension->afterDelegation($method, $annotation, $this->componentFacade->getClassName(), $facadeMethod->getDelegatedClassName(), $methodName, $facadeMethod->getMethodParameterList());
        }
    }

}