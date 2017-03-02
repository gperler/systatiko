<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Component2\Entity;

class OverWriteEntity extends BaseEntity
{

    /**
     * @Factory(
     *     namespace="SystatikoTest\End2End\Asset\Generated\Component2",
     *     context="X",
     *     overwrites="SystatikoTest\End2End\Asset\Component2\Entity\BaseEntity"
     * )
     * SampleEntity2 constructor.
     */
    public function __construct()
    {
    }

}