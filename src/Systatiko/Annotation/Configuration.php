<?php

namespace Systatiko\Annotation;

use Attribute;


/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Configuration
{

    public function __construct(public string $namespace)
    {
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return trim($this->namespace, "\\");
    }

}