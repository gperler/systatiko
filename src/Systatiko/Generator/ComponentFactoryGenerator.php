<?php

namespace Systatiko\Generator;

use Nitria\ClassGenerator;
use Nitria\Method;
use Systatiko\Configuration\GeneratorConfiguration;
use Systatiko\Model\ComponentEvent;
use Systatiko\Model\ComponentFacade;
use Systatiko\Model\ComponentFactory;
use Systatiko\Model\ComponentFactoryMethod;
use Systatiko\Model\Project;
use Systatiko\Model\ProjectClass;
use Systatiko\Reader\PHPMethod;

class ComponentFactoryGenerator
{

    /**
     * @var Project
     */
    private Project $project;

    /**
     * @var ComponentFactory
     */
    private ComponentFactory $componentFactory;

    /**
     * @var ProjectClass|null
     */
    private ?ProjectClass $componentConfigurationClass;

    /**
     * @var ComponentFacade|null
     */
    private ?ComponentFacade $componentFacade;

    /**
     * @var string
     */
    private string $backboneClassName;

    /**
     * @var ClassGenerator
     */
    private ClassGenerator $classGenerator;


    /**
     * ComponentFactoryGenerator constructor.
     *
     * @param Project $project
     * @param ComponentFactory $componentFactory
     */
    public function __construct(Project $project, ComponentFactory $componentFactory)
    {
        $this->project = $project;
        $this->componentFactory = $componentFactory;
        $this->componentConfigurationClass = $componentFactory->getComponentConfigurationClass();
        $this->componentFacade = $componentFactory->getComponentFacade();
    }


    /**
     * @param GeneratorConfiguration $configuration
     */
    public function generate(GeneratorConfiguration $configuration): void
    {
        $this->backboneClassName = $configuration->getBackboneClassName();

        $this->classGenerator = new ClassGenerator($this->componentFactory->getClassName());

        $this->addMember();

        $this->addConstructor();

        $this->addConfigurationAccess();

        $this->addFacadeAccess();

        $this->addAccessMethodList();

        $this->addTriggerMethodList();

        $this->addBackboneExposeList();

        $this->addResetSingletonMethod();

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
        $this->classGenerator->addProtectedProperty("backbone", $this->backboneClassName);

        if ($this->componentFacade !== null) {
            $this->classGenerator->addProtectedProperty($this->componentFacade->getMemberName(), $this->componentFacade->getClassName(), 'null');
        }

        if ($this->componentConfigurationClass !== null) {
            $this->classGenerator->addProtectedProperty($this->componentConfigurationClass->getMemberName(), $this->componentConfigurationClass->getClassName(), 'null');
        }

        foreach ($this->componentFactory->getComponentFactoryMethodList() as $componentClass) {
            if (!$componentClass->isSingleton()) {
                continue;
            }
            $this->classGenerator->addProtectedProperty($componentClass->getMemberName(), $componentClass->getClassName(), 'null');
        }
    }


    /**
     *
     */
    private function addConstructor()
    {
        $constructor = $this->classGenerator->addMethod("__construct");
        $constructor->addParameter($this->backboneClassName, "backbone");

        $constructor->addCodeLine('$this->backbone = $backbone;');
    }


    /**
     *
     */
    private function addResetSingletonMethod()
    {
        $resetMethod = $this->classGenerator->addMethod("resetSingleton");
        if ($this->componentFacade !== null) {
            $resetMethod->addCodeLine('$this->' . $this->componentFacade->getMemberName() . ' = null;');
        }
        foreach ($this->componentFactory->getComponentFactoryMethodList() as $componentClass) {
            if (!$componentClass->isSingleton()) {
                continue;
            }
            $resetMethod->addCodeLine('$this->' . $componentClass->getMemberName() . ' = null;');
        }
    }


    /**
     *
     */
    private function addConfigurationAccess()
    {
        if ($this->componentConfigurationClass === null) {
            return;
        }
        $classShortName = $this->componentConfigurationClass->getClassShortName();
        $configurationClassName = $this->componentConfigurationClass->getClassName();
        $member = '$this->' . $this->componentConfigurationClass->getMemberName();
        $componentName = $this->componentFactory->getComponentName();

        $method = $this->classGenerator->addMethod("getConfiguration");
        $method->setReturnType($configurationClassName);

        $method->addIfStart("$member === null");
        $method->addCodeLine("$member = new $classShortName();");
        $method->addCodeLine($member . '->setValueList($this->backbone->getComponentConfiguration("' . $componentName . '"));');
        $method->addIfEnd();

        $method->addCodeLine("return $member;");
    }


    /**
     *
     */
    private function addFacadeAccess(): void
    {
        if ($this->componentFacade === null) {
            return;
        }
        $facadeClassShortName = $this->componentFacade->getClassShortName();
        $facadeClassName = $this->componentFacade->getClassName();
        $methodName = $this->componentFacade->getFactoryMethodName();
        $memberFullName = '$this->' . $this->componentFacade->getMemberName();

        $method = $this->classGenerator->addMethod($methodName);
        $method->setReturnType($facadeClassName, false);

        $method->addIfStart($memberFullName . " === null");
        $method->addCodeLine($memberFullName . " = new " . $facadeClassShortName . '(');
        $method->incrementIndent();
        $method->addCodeLine('$this->backbone,');
        $method->addCodeLine('$this,');
        $method->decrementIndent();
        $method->addCodeLine(');');
        $method->addIfEnd();
        $method->addCodeLine("return $memberFullName;");
    }


    /**
     *
     */
    private function addTriggerMethodList(): void
    {
        foreach ($this->componentFactory->getComponentEventList() as $componentEvent) {
            $this->addTriggerMethod($componentEvent);
        }
    }


    /**
     * @param ComponentEvent $componentEvent
     */
    private function addTriggerMethod(ComponentEvent $componentEvent): void
    {
        $method = $this->classGenerator->addMethod($componentEvent->getTriggerMethodName());
        $method->addParameter($componentEvent->getEventClassName(), "event");

        if ($componentEvent->isAsynchronousEvent()) {
            $method->addCodeLine('$this->backbone->dispatchOutboundAsynchronousEvent($event);');
        }

        if ($componentEvent->isSynchronousEvent()) {
            $this->addSynchronousEventHandling($componentEvent, $method);
        }

        $this->addInternalDispatching($componentEvent, $method);
    }


    /**
     * @param ComponentEvent $componentEvent
     * @param Method $method
     */
    private function addInternalDispatching(ComponentEvent $componentEvent, Method $method): void
    {
        foreach ($componentEvent->getEventHandlerList() as $eventHandler) {
            $facadeAccess = $eventHandler->getFacadeAccessMethod();
            $facadeMethodName = $eventHandler->getFacadeMethodName();

            $method->addCodeLine('$this->backbone->' . $facadeAccess . '()->' . $facadeMethodName . '($event);');
        }
    }


    /**
     * @param ComponentEvent $componentEvent
     * @param Method $method
     */
    private function addSynchronousEventHandling(ComponentEvent $componentEvent, Method $method): void
    {
        $method->addCodeLine('$this->backbone->dispatchSynchronousEvent($event);');
    }


    /**
     *
     */
    private function addBackboneExposeList(): void
    {
        foreach ($this->project->getGlobalExposeMethodList() as $phpMethod) {
            $this->addBackboneExpose($phpMethod);
        }
    }


    /**
     * @param PHPMethod $exposedMethod
     */
    private function addBackboneExpose(PHPMethod $exposedMethod): void
    {
        $method = $this->classGenerator->addMethod($exposedMethod->getMethodName());

        foreach ($exposedMethod->getMethodParameterList() as $parameter) {
            if ($parameter->isAsClassName()) {
                $className = $parameter->getClassName();
                $this->classGenerator->addUsedClassName($className->getClassName(), $className->getAs());
            }
            $fqn = $parameter->getFullyQualifiedName();
            $method->addParameter($fqn, $parameter->getName(), $parameter->getNitriaDefault());
        }

        $methodReturnType = $exposedMethod->getMethodReturnType();

        $optional = $methodReturnType->canBeNull();
        $docBlockReturnType = $methodReturnType->getFullyQualifiedName();
        $method->setReturnType($docBlockReturnType, $optional);
        $return = $method->hasReturnType() ? 'return ' : '';
        $invocationSignature = $exposedMethod->getInvocationSignature();
        $method->addCodeLine($return . '$this->backbone->' . $exposedMethod->getMethodName() . "($invocationSignature);");
    }


    /**
     *
     */
    private function addAccessMethodList(): void
    {
        foreach ($this->componentFactory->getComponentFactoryMethodList() as $componentClass) {
            $this->addAccessMethod($componentClass);
        }
    }


    /**
     * @param ComponentFactoryMethod $componentFactoryMethod
     */
    private function addAccessMethod(ComponentFactoryMethod $componentFactoryMethod)
    {
        $this->classGenerator->addUsedClassName($componentFactoryMethod->getClassName());

        $methodName = $componentFactoryMethod->getFactoryMethodName();
        $method = $this->classGenerator->addMethod($methodName);

        $returnType = $componentFactoryMethod->getFactoryMethodReturnType();
        $method->setReturnType($returnType, false);

        foreach ($componentFactoryMethod->getAccessorParameterList() as $parameter) {
            if ($parameter->isAsClassName()) {
                $className = $parameter->getClassName();
                $this->classGenerator->addUsedClassName($className->getClassName(), $className->getAs());
            }
            $fqn = $parameter->getFullyQualifiedName();
            $method->addParameter($fqn, $parameter->getName(), $parameter->getNitriaDefault());
        }

        if ($componentFactoryMethod->hasOverwritingComponentFactoryClassList()) {
            $this->addContextAwareAccessBlock($componentFactoryMethod, $method);
        } else {
            $this->addAccessBlock($componentFactoryMethod, $method);
        }
    }


    /**
     * @param ComponentFactoryMethod $componentFactoryClass
     * @param Method $method
     */
    private function addContextAwareAccessBlock(ComponentFactoryMethod $componentFactoryClass, Method $method): void
    {
        $method->addSwitch('$this->backbone->getContext()');

        foreach ($componentFactoryClass->getOverwritingComponentFactoryClassList() as $overwriting) {
            $method->addSwitchCase('"' . $overwriting->getContext() . '"');
            $this->addAccessBlock($overwriting, $method);
            $method->addSwitchReturnBreak();
        }

        $method->addSwitchDefault();
        $this->addAccessBlock($componentFactoryClass, $method);
        $method->addSwitchReturnBreak();
        $method->addSwitchEnd();
    }


    /**
     * @param ComponentFactoryMethod $componentFactoryClass
     * @param Method $method
     */
    private function addAccessBlock(ComponentFactoryMethod $componentFactoryClass, Method $method): void
    {
        $memberFullName = '$this->' . $componentFactoryClass->getMemberName();

        if (!$componentFactoryClass->isSingleton()) {
            $this->addGenerateNewInstance(
                $componentFactoryClass,
                $method,
                'return'
            );
            return;
        }
        $method->addIfStart($memberFullName . " === null");
        $this->addGenerateNewInstance(
            $componentFactoryClass,
            $method,
            "$memberFullName ="
        );
        $method->addIfEnd();
        $method->addCodeLine("return $memberFullName;");
    }


    /**
     * @param ComponentFactoryMethod $componentFactoryClass
     *
     * @return string
     */
    private function generateNewInstance(ComponentFactoryMethod $componentFactoryClass): string
    {
        $signature = $componentFactoryClass->getConstructorInvocationSignature();

        $parameterList = $componentFactoryClass->getConstructorInvocationSignatureList();

        if (count($parameterList) === 0) {
            return "new " . $componentFactoryClass->getClassShortName() . "();";
        }


        return "new " . $componentFactoryClass->getClassShortName() . "($signature);";
    }


    /**
     * @param ComponentFactoryMethod $componentFactoryClass
     * @param Method $method
     * @param string $prefix
     *
     * @return void
     */
    private function addGenerateNewInstance(ComponentFactoryMethod $componentFactoryClass, Method $method, string $prefix): void
    {
        $classShortName = $componentFactoryClass->getClassShortName();
        $parameterList = $componentFactoryClass->getConstructorInvocationSignatureList();

        if (count($parameterList) === 0) {
            $method->addCodeLine("$prefix new $classShortName();");
            return;
        }

        $method->addCodeLine("$prefix new $classShortName(");
        $method->incrementIndent();
        foreach ($parameterList as $parameter) {
            $method->addCodeLine($parameter . ',');
        }
        $method->decrementIndent();
        $method->addCodeLine(');');
    }


}