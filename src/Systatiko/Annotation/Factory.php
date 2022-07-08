<?php

namespace Systatiko\Annotation;

use Civis\Common\StringUtil;

/**
 * @Annotation
 */
class Factory
{

    public $namespace;

    public $context;

    public $overwrites;

    public $singleton;

    public $returnType;

    public $noInjection;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return trim($this->namespace, "\\ ");
    }

    /**
     * @return string
     */
    public function getOverwrites()
    {
        return trim($this->overwrites, "\\ ");
    }

    /**
     * @return bool
     */
    public function isSingleton() : bool
    {
        return $this->singleton === true;
    }

    /**
     * @return null|string
     */
    public function getContext()
    {
        return StringUtil::trimToNull($this->context);
    }

    /**
     * @param string $paramName
     *
     * @return bool
     */
    public function injectionAllowed(string $paramName)
    {
        if ($this->noInjection === null) {
            return true;
        }
        return strpos($this->noInjection, $paramName) === false;
    }

}