<?php

declare(strict_types = 1);

namespace Systatiko\Annotation;

/**
 * @Annotation
 */
class Event
{

    /**
     * @var string
     */
    public $namespace;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return trim($this->namespace, "\\");
    }
}