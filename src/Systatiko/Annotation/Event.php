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
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return trim($this->namespace, "\\");
    }
}