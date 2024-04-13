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

    public function __construct(public string $namespace, public string $name)
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