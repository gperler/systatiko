<?php

namespace Systatiko\Contract;

use Nitria\Method;
use Systatiko\Reader\PHPParameter;

interface FacadeGeneratorExtension
{

    /**
     * @return string
     */
    public function getAnnotationClassName() : string;

    /**
     * @param Method $method
     * @param annotation
     * @param string $facadeName
     * @param string $delegatedClass
     * @param string $delegatedMethodName
     * @param PHPParameter[] $parameterList
     */
    public function beforeDelegation(Method $method, $annotation, string $facadeName, string $delegatedClass, string $delegatedMethodName, array $parameterList);

    /**
     * @param Method $method
     * @param annotation
     * @param string $facadeName
     * @param string $delegatedClass
     * @param string $delegatedMethodName
     * @param PHPParameter[] $parameterList
     */
    public function afterDelegation(Method $method, $annotation, string $facadeName, string $delegatedClass, string $delegatedMethodName, array $parameterList);

}