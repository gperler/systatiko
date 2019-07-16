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
use Systatiko\Reader\PHPParameter;

class ComponentFactoryGenerator
{

    /**
     * @var Project
     */
    private $project;

    /**
     * @var ComponentFactory
     */
    private $componentFactory;

    /**
     * @var ProjectClass
     */
    private $componentConfigurationClass;

    /**
     * @var ComponentFacade
     */
    private $componentFacade;

    /**
     * @var
     */
    private $backboneClassName;

    /**
     * @var ClassGenerator
     */
    private $classGenerator;

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
    public function generate(GeneratorConfiguration $configuration)
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
            $this->classGenerator->addProtectedProperty($this->componentFacade->getMemberName(), $this->componentFacade->getClassName());
        }

        if ($this->componentConfigurationClass !== null) {
            $this->classGenerator->addProtectedProperty($this->componentConfigurationClass->getMemberName(), $this->componentConfigurationClass->getClassName());
        }

        foreach ($this->componentFactory->getComponentFactoryMethodList() as $componentClass) {
            if (!$componentClass->isSingleton()) {
                continue;
            }
            $this->classGenerator->addProtectedProperty($componentClass->getMemberName(), $componentClass->getClassName());
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
    private function addFacadeAccess()
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
        $method->addCodeLine($memberFullName . " = new " . $facadeClassShortName . '($this->backbone, $this);');
        $method->addIfEnd();
        $method->addCodeLine("return $memberFullName;");

    }

    /**
     *
     */
    private function addTriggerMethodList()
    {
        $eventCount = sizeof($this->componentFactory->getComponentEventList());
        foreach ($this->componentFactory->getComponentEventList() as $componentEvent) {
            $this->addTriggerMethod($componentEvent);
        }
    }

    /**
     * @param ComponentEvent $componentEvent
     */
    private function addTriggerMethod(ComponentEvent $componentEvent)
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
    private function addInternalDispatching(ComponentEvent $componentEvent, Method $method)
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
    private function addSynchronousEventHandling(ComponentEvent $componentEvent, Method $method)
    {
        $method->addCodeLine('$this->backbone->dispatchSynchronousEvent($event);');
    }

    private function addBackboneExposeList()
    {
        foreach ($this->project->getGlobalExposeMethodList() as $phpMethod) {
            $this->addBackboneExpose($phpMethod);
        }
    }

    private function addBackboneExpose(PHPMethod $exposedMethod)
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
    private function addAccessMethodList()
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
    private function addContextAwareAccessBlock(ComponentFactoryMethod $componentFactoryClass, Method $method)
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
    private function addAccessBlock(ComponentFactoryMethod $componentFactoryClass, Method $method)
    {

        $memberFullName = '$this->' . $componentFactoryClass->getMemberName();
        $newInstance = $this->generateNewInstance($componentFactoryClass);

        if (!$componentFactoryClass->isSingleton()) {
            $method->addCodeLine("return $newInstance");
            return;
        }
        $method->addIfStart($memberFullName . " === null");
        $method->addCodeLine($memberFullName . " = " . $newInstance);
        $method->addIfEnd();
        $method->addCodeLine("return $memberFullName;");
    }

    /**
     * @param ComponentFactoryMethod $componentFactoryClass
     *
     * @return string
     */
    private function generateNewInstance(ComponentFactoryMethod $componentFactoryClass)
    {
        $signature = $componentFactoryClass->getConstructorInvocationSignature();

        return "new " . $componentFactoryClass->getClassShortName() . "($signature);";
    }

    /**
     * @param PHPParameter $parameter
     *
     * @return string
     */
    private function getConstructorParameterValue(PHPParameter $parameter)
    {
        if ($parameter->getClassName() === $this->componentFactory->getClassName()) {
            return '$this';
        }
        return '""';
    }

}