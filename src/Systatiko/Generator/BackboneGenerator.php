<?php

namespace Systatiko\Generator;

use Nitria\ClassGenerator;
use Nitria\Method;
use Systatiko\Configuration\GeneratorConfiguration;
use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Exception\EventNotDefinedException;
use Systatiko\Model\ComponentEvent;
use Systatiko\Model\ComponentFacade;

class BackboneGenerator
{

    /**
     * @var ComponentFacade[]
     */
    private $componentFacadeList;

    /**
     * @var ComponentEvent[]
     */
    private $componentEventList;

    /**
     * @var ClassGenerator
     */
    private $classGenerator;

    /**
     * @var GeneratorConfiguration
     */
    private $configuration;

    /**
     * BackboneGenerator constructor.
     *
     * @param ComponentFacade[] $componentFacadeList
     * @param ComponentEvent[] $componentEventList
     */
    public function __construct(array $componentFacadeList, array $componentEventList)
    {
        $this->componentFacadeList = $componentFacadeList;
        $this->componentEventList = $componentEventList;
    }

    /**
     * @param GeneratorConfiguration $configuration
     */
    public function generate(GeneratorConfiguration $configuration)
    {
        $this->configuration = $configuration;

        $this->classGenerator = new ClassGenerator($configuration->getBackboneClassName());

        $this->classGenerator->setExtends($configuration->getBackboneExtendsClassName());

        $this->addSingleton();

        $this->addMember();

        $this->addAccessMethodList();

        $this->addNewAsynchronousEvent();

        $this->addDispatchInboundAsynchronousEvent();

        $this->addResetSingleton();

        if ($configuration->isPSR0()) {
            $this->classGenerator->writeToPSR0($configuration->getTargetDir());
        }

        if ($configuration->isPSR4()) {
            $this->classGenerator->writeToPSR4($configuration->getTargetDir(), $configuration->getPSR4Prefix());
        }
    }

    private function addSingleton()
    {
        $backboneClassName = $this->configuration->getBackboneClassName();
        $backboneClassShortName = $this->configuration->getBackboneClassShortName();

        $this->classGenerator->addUsedClassName('Civis\Common\File');
        $this->classGenerator->addProtectedStaticProperty('instance', $backboneClassName, 'null');

        $method = $this->classGenerator->addPublicStaticMethod("getInstance");
        $method->addParameter("string", "configFileName", "null");
        $method->setReturnType($backboneClassName, false);

        $method->addIfStart('self::$instance === null');
        $method->addCodeLine('self::$instance = new ' . $backboneClassShortName . '();');
        $method->addIfEnd();

        $method->addIfStart('$configFileName !== null');
        $method->addCodeLine('self::$instance->setConfigurationFile(new File($configFileName));');
        $method->addIfEnd();

        $method->addCodeLine('return self::$instance;');
    }

    /**
     *
     */
    private function addMember()
    {

        foreach ($this->componentFacadeList as $componentFacade) {
            $memberName = $componentFacade->getFactoryMemberName();
            $memberType = $componentFacade->getFactoryClassName();
            $this->classGenerator->addProtectedProperty($memberName, $memberType, 'null');
        }
    }

    private function addResetSingleton()
    {
        $resetMethod = $this->classGenerator->addMethod("resetSingleton");

        foreach ($this->componentFacadeList as $componentFacade) {
            $memberName = $componentFacade->getFactoryMemberName();
            $resetMethod->addCodeLine('$this->' . $memberName . ' = null;');
        }
    }

    /**
     *
     */
    private function addAccessMethodList()
    {
        foreach ($this->componentFacadeList as $componentFacade) {
            $this->addFacadeAccess($componentFacade);
            $this->addFactoryAccess($componentFacade);
        }
    }

    /**
     * @param ComponentFacade $componentFacade
     */
    private function addFacadeAccess(ComponentFacade $componentFacade)
    {

        $methodName = $componentFacade->getFactoryMethodName();
        $componentClassName = $componentFacade->getClassName();

        $method = $this->classGenerator->addMethod($methodName);
        $method->setReturnType($componentClassName, false);
        $factoryMethodName = "get" . $componentFacade->getFactoryClassShortName();
        $method->addCodeLine('return $this->' . $factoryMethodName . "()->" . $methodName . '();');

    }

    /**
     * @param ComponentFacade $componentFacade
     */
    private function addFactoryAccess(ComponentFacade $componentFacade)
    {
        $factoryClassShortName = $componentFacade->getFactoryClassShortName();

        $methodName = "get" . $factoryClassShortName;
        $memberName = '$this->' . $componentFacade->getFactoryMemberName();
        $componentFactoryClassName = $componentFacade->getFactoryClassName();

        $method = $this->classGenerator->addMethod($methodName);
        $method->setReturnType($componentFactoryClassName, false);

        $method->addIfStart($memberName . ' === null');
        $method->addCodeLine($memberName . ' = new ' . $factoryClassShortName . '($this); ');
        $method->addIfEnd();
        $method->addCodeLine('return ' . $memberName . ';');
    }

    private function addNewAsynchronousEvent()
    {
        $this->classGenerator->addUsedClassName(EventNotDefinedException::class);

        $method = $this->classGenerator->addPublicMethod("newAsynchronousEvent");
        $method->addException(EventNotDefinedException::class);
        $method->addParameter('string', 'eventName');
        $method->addParameter('array', 'payload');
        $method->setReturnType(AsynchronousEvent::class, false);

        $method->addSwitch('$eventName');
        foreach ($this->componentEventList as $componentEvent) {
            if (!$componentEvent->isAsynchronousEvent()) {
                continue;
            }
            $factory = $componentEvent->getResponsibleFactory();

            $method->addSwitchCase('"' . $componentEvent->getEventName() . '"');
            $method->addCodeLine('$event = $this->get' . $factory->getClassShortName() . '()->new' . $componentEvent->getEventClassShortName() . '();');
            $method->addSwitchBreak();
        }

        $method->addSwitchDefault();
        $method->addCodeLine('throw new EventNotDefinedException($eventName . " not defined");');
        $method->addSwitchReturnBreak();
        $method->addSwitchEnd();

        $method->addCodeLine('$event->fromPayload($payload);');
        $method->addCodeLine('return $event;');
    }

    /**
     *
     */
    private function addDispatchInboundAsynchronousEvent()
    {
        $method = $this->classGenerator->addPublicMethod("dispatchInboundAsynchronousEvent");
        $method->addParameter(AsynchronousEvent::class, "event");

        foreach ($this->componentEventList as $componentEvent) {
            $this->addComponentEventDispatcher($componentEvent, $method);
        }
    }

    /**
     * @param ComponentEvent $componentEvent
     * @param Method $method
     */
    private function addComponentEventDispatcher(ComponentEvent $componentEvent, Method $method)
    {
        if (!$componentEvent->isAsynchronousEvent()) {
            return;
        }

        $this->classGenerator->addUsedClassName($componentEvent->getEventClassName());

        $method->addIfStart('$event instanceof ' . $componentEvent->getEventClassShortName());

        foreach ($componentEvent->getEventHandlerList() as $eventHandler) {
            $method->addCodeLine('$this->' . $eventHandler->getFacadeAccessMethod() . '()->' . $eventHandler->getFacadeMethodName() . '($event);');
        }

        $method->addIfEnd();
    }

}