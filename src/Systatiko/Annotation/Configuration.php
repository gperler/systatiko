<?php

namespace Systatiko\Annotation;

use Attribute;


/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Configuration
{

    /**
     * @var string
     */
    public string $namespace;


    /**
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return trim($this->namespace, "\\");
    }

}