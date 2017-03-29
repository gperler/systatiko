<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Extension;

use Nitria\Method;
use Systatiko\Contract\FacadeGeneratorExtension;
use Systatiko\Reader\PHPParameter;

class GeneratorExtension implements FacadeGeneratorExtension
{

    /**
     * @return string
     */
    public function getAnnotationClassName() : string
    {
        return 'SystatikoTest\End2End\Asset\Extension\CustomAnnotation';
    }

    /**
     * @param Method $method
     * @param annotation
     * @param string $facadeName
     * @param string $delegatedClass
     * @param string $delegatedMethodName
     * @param PHPParameter[] $parameterList
     */
    public function beforeDelegation(Method $method, $annotation, string $facadeName, string $delegatedClass, string $delegatedMethodName, array $parameterList)
    {
        $method->addCodeLine('$this->backbone->getComponent2Facade()->isInRole("' . $annotation->roleRequired .  '");');
    }

    /**
     * @param Method $method
     * @param annotation
     * @param string $facadeName
     * @param string $delegatedClass
     * @param string $delegatedMethodName
     * @param PHPParameter[] $parameterList
     */
    public function afterDelegation(Method $method, $annotation, string $facadeName, string $delegatedClass, string $delegatedMethodName, array $parameterList)
    {

    }
}