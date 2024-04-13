<?php

namespace Systatiko\Annotation;

use Civis\Common\StringUtil;
use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Factory
{

    public function __construct(public ?string $namespace, public ?string $context = null, public ?string $overwrites = null, public ?bool   $singleton = false, public ?string $returnType = null, public ?string $noInjection = null)
    {
    }


    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return trim((string) $this->namespace, "\\ ");
    }

    /**
     * @return string
     */
    public function getOverwrites(): string
    {
        return trim((string) $this->overwrites, "\\ ");
    }

    /**
     * @return bool
     */
    public function isSingleton(): bool
    {
        return $this->singleton === true;
    }

    /**
     * @return null|string
     */
    public function getContext(): ?string
    {
        return StringUtil::trimToNull($this->context);
    }

    /**
     * @return bool
     */
    public function injectionAllowed(string $paramName): bool
    {
        if ($this->noInjection === null) {
            return true;
        }
        return !str_contains($this->noInjection, $paramName);
    }

}