<?php

namespace Systatiko\Generator;

use Nitria\ClassGenerator;
use Systatiko\Configuration\GeneratorConfiguration;
use Systatiko\Model\ComponentFacade;

class FacadeLocatorGenerator
{

    /**
     * @var ComponentFacade[]
     */
    protected $componentFacadeList;

    /**
     * @var ClassGenerator
     */
    protected $classGenerator;

    /**
     * FacadeLocatorGenerator constructor.
     *
     * @param array $componentFacadeList
     */
    public function __construct(array $componentFacadeList)
    {
        $this->componentFacadeList = $componentFacadeList;
    }

    /**
     * @param GeneratorConfiguration $configuration
     */
    public function generate(GeneratorConfiguration $configuration)
    {

        $this->classGenerator = new ClassGenerator($configuration->getFacadeLocatorClassName());

        $this->classGenerator->setExtends($configuration->getFacadeExtendsClassName());

        $this->addMember();

        $this->addAccessMethodList();

        $this->classGenerator->writeToPSR0($configuration->getTargetDir());
    }

    /**
     *
     */
    protected function addMember()
    {
        foreach ($this->componentFacadeList as $componentFacade) {
            $memberName = $componentFacade->getFactoryMemberName();
            $memberType = $componentFacade->getFactoryClassName();
            $this->classGenerator->addProtectedProperty($memberName, $memberType);
        }
    }

    /**
     *
     */
    protected function addAccessMethodList()
    {
        foreach ($this->componentFacadeList as $componentFacade) {
            $this->addFacadeAccess($componentFacade);
            $this->addFactoryAccess($componentFacade);
        }
    }

    /**
     * @param ComponentFacade $componentFacade
     */
    protected function addFacadeAccess(ComponentFacade $componentFacade)
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
    protected function addFactoryAccess(ComponentFacade $componentFacade)
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

}