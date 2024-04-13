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
     * @param annotation
     * @param PHPParameter[] $parameterList
     */
    public function beforeDelegation(Method $method, $annotation, string $facadeName, string $delegatedClass, string $delegatedMethodName, array $parameterList);

    /**
     * @param annotation
     * @param PHPParameter[] $parameterList
     */
    public function afterDelegation(Method $method, $annotation, string $facadeName, string $delegatedClass, string $delegatedMethodName, array $parameterList);

}