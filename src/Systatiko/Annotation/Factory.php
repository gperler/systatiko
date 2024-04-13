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

    /**
     * @var string|null
     */
    public ?string $namespace;

    /**
     * @var string|null
     */
    public ?string $context;

    /**
     * @var string|null
     */
    public ?string $overwrites;

    /**
     * @var bool|null
     */
    public ?bool $singleton;

    /**
     * @var string|null
     */
    public ?string $returnType;

    /**
     * @var string|null
     */
    public ?string $noInjection;

    /**
     * @param string|null $namespace
     * @param string|null $context
     * @param string|null $overwrites
     * @param bool|null $singleton
     * @param string|null $returnType
     * @param string|null $noInjection
     */
    public function __construct(
        ?string $namespace,
        string $context = null,
        string $overwrites = null,
        bool   $singleton = false,
        string $returnType = null,
        string $noInjection = null,
    )
    {
        $this->namespace = $namespace;
        $this->context = $context;
        $this->overwrites = $overwrites;
        $this->singleton = $singleton;
        $this->returnType = $returnType;
        $this->noInjection = $noInjection;
    }


    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return trim($this->namespace, "\\ ");
    }

    /**
     * @return string
     */
    public function getOverwrites(): string
    {
        return trim($this->overwrites, "\\ ");
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
     * @param string $paramName
     *
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