<?php

namespace Systatiko\Annotation;

/**
 * @Annotation
 */
class Configuration
{

    public $namespace;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return trim($this->namespace, "\\");
    }

}