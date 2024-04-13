<?php

namespace Systatiko\Annotation;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class FacadeExposition
{

    /**
     * @var string|null
     */
    public ?string $namespace;

    /**
     * @var string|null
     */
    public ?string $factoryClassName;

    /**
     * @param string|null $namespace
     * @param string|null $factoryClassName
     */
    public function __construct(string $namespace = null, string $factoryClassName = null)
    {
        $this->namespace = $namespace;
        $this->factoryClassName = $factoryClassName;
    }

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