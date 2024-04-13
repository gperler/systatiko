<?php

namespace Systatiko\Generator;

use Nitria\ClassGenerator;
use Nitria\Method;
use Systatiko\Configuration\GeneratorConfiguration;
use Systatiko\Model\ComponentFacade;
use Systatiko\Model\ComponentFacadeMethod;
use Systatiko\Model\Project;
use Systatiko\Reader\PHPMethodReturnType;

class ComponentFacadeGenerator
{

    /**
     * @var ClassGenerator
     */
    private $classGenerator;

    /**
     * @var GeneratorConfiguration
     */
    private $configuration;


    /**
     * ComponentFacadeGenerator constructor.
     */
    public function __construct(private readonly Project $project, private readonly ComponentFacade $componentFacade)
    {
    }


    public function generate(GeneratorConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->classGenerator = new ClassGenerator($this->componentFacade->getClassName());

        $this->addMember();

        $this->addConstructor();

        $this->addFacadeMethodList();

        if ($configuration->isPSR0()) {
            $this->classGenerator->writeToPSR0($configuration->getTargetDir());
        }

        if ($configuration->isPSR4()) {
            $this->classGenerator->writeToPSR4($configuration->getTargetDir(), $configuration->getPSR4Prefix());
        }
    }


    /**
     *
     */
    private function addMember()
    {
        $this->classGenerator->addProtectedProperty("backbone", $this->configuration->getBackboneClassName());
        $this->classGenerator->addProtectedProperty("factory", $this->componentFacade->getFactoryClassName());
    }


    /**
     *
     */
    private function addConstructor()
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
    private function addFacadeMethodList()
    {
        foreach ($this->componentFacade->getComponentFacadeMethodList() as $method) {
            $this->addFacadeMethod($method);
        }
    }


    private function addFacadeMethod(ComponentFacadeMethod $facadeMethod)
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

        foreach ($facadeMethod->getThrownExceptionList() as $exception) {
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

        if ($methodReturnType->getSignatureType() === 'void') {
            $method->setReturnType('void', false);
        }


        $this->addBeforeDelegation($facadeMethod, $method, $methodName);

        $return = $this->getReturn($method, $methodReturnType);
        $method->addCodeLine($return . '$this->factory->' . $facadeMethod->getFactoryMethodName() . "()->$methodName($invocationSignature);");

        $this->addAfterDelegation($facadeMethod, $method, $methodName);
    }


    /**
     *
     * @return string
     */
    private function getReturn(Method $method, PHPMethodReturnType $methodReturnType): string
    {
        if ($methodReturnType->getSignatureType() === 'void') {
            return '';
        }
        $docBlockReturnType = $methodReturnType->getFullyQualifiedName();

        if (($method->hasReturnType() || $docBlockReturnType === 'mixed')) {
            return 'return ';
        }
        return '';
    }


    private function addBeforeDelegation(ComponentFacadeMethod $facadeMethod, Method $method, string $methodName)
    {
        foreach ($this->configuration->getFacadeGeneratorExtension() as $extension) {
            $annotation = $facadeMethod->getMethodOrClassAnnotation($extension->getAnnotationClassName());
            if ($annotation === null) {
                continue;
            }
            $extension->beforeDelegation($method, $annotation, $this->componentFacade->getClassName(), $facadeMethod->getDelegatedClassName(), $methodName, $facadeMethod->getMethodParameterList());
        }
    }


    private function addAfterDelegation(ComponentFacadeMethod $facadeMethod, Method $method, string $methodName)
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