<?php

declare(strict_types=1);

namespace Systatiko\Annotation;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Event
{

    /**
     * @var string
     */
    public string $namespace;

    /**
     * @var string
     */
    public string $name;

    /**
     * @param string $namespace
     * @param string $name
     */
    public function __construct(string $namespace, string $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return trim($this->namespace, "\\");
    }
}