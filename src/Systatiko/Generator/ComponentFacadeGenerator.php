<?php

namespace Systatiko\Generator;


use Nitria\ClassGenerator;
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
        $this->classGenerator->addProtectedProperty("factory", $this->componentFacade->getClassName());
    }

    /**
     *
     */
    protected function addConstructor()
    {
        $constructor = $this->classGenerator->addMethod("__construct");
        $constructor->addParameter($this->componentFacade->getFactoryClassName(), 'factory');
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
            $method->addParameter($fqn, $parameter->getName(), $parameter->getDefault());
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

        $return = $method->hasReturnType() || $docBlockReturnType === 'mixed' ? 'return ' : '';
        $method->addCodeLine($return . '$this->factory->' . $facadeMethod->getFactoryMethodName() . "()->$methodName($invocationSignature);");

    }

}