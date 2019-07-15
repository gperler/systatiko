<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component1\Model;

use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Component1\Entity\SampleEntity;

class NoInjection
{

    /**
     * @var SampleEntity
     */
    protected $entity;

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component1", singleton=true, noInjection="$entity")
     *
     * @param SampleEntity $entity
     */
    public function __construct(SampleEntity $entity)
    {
        $this->entity = $entity;
    }


    /**
     * @return string
     */
    public function getTestValue()
    {
        return $this->entity->test;
    }
}