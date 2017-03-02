<?php

namespace Systatiko\Annotation;

/**
 * @Annotation
 */
class FacadeExposition
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