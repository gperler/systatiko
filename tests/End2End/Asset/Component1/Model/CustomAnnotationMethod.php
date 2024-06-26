<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Extension\CustomAnnotation;


#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1')]
class CustomAnnotationMethod
{
    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component1', singleton: false)] // ServiceClass constructor.
    public function __construct()
    {

    }

    /**
     *
     */
    #[FacadeExposition]
    #[CustomAnnotation(roleRequired: "myRole")]
    public function methodWithCustomExtension()
    {

    }

    #[FacadeExposition]
    public function methodWithoutCustomExtension()
    {

    }

}