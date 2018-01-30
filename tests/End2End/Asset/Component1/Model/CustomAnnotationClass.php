<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component1")
 * @CustomAnnotation(roleRequired="myRole")
 */
class CustomAnnotationClass
{

    /**
     * @Factory(
     *     namespace="SystatikoTest\End2End\Asset\Generated\Component1",
     *     singleton=false,
     * )
     * ServiceClass constructor.
     */
    public function __construct()
    {

    }

    /**
     * @FacadeExposition
     */
    public function testMethod1() {

    }

    /**
     * @FacadeExposition
     */
    public function testMethod2() {

    }

}