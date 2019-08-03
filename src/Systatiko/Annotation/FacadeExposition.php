<?php

namespace Systatiko\Annotation;

/**
 * @Annotation
 */
class FacadeExposition
{

    public $namespace;

    public $factoryClassName;


    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return trim($this->namespace, "\\");
    }


    /**
     * @return string|null
     */
    public function getFactoryClassName(): ?string
    {
        return $this->factoryClassName;
    }

}