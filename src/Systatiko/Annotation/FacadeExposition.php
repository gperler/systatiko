<?php

namespace Systatiko\Annotation;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class FacadeExposition
{

    public function __construct(public ?string $namespace = null, public ?string $factoryClassName = null)
    {
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return trim((string) $this->namespace, "\\");
    }


    /**
     * @return string|null
     */
    public function getFactoryClassName(): ?string
    {
        return $this->factoryClassName;
    }

}