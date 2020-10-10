<?php

declare(strict_types=1);

namespace Systatiko\Configuration;

class InjectionConfiguration
{

    /**
     * @var array
     */
    private $configValues;


    /**
     * InjectionConfiguration constructor.
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->configValues = $config;
    }

    /**
     * @param string $className
     * @return bool
     */
    public function isDefined(string $className): bool
    {
        if ($this->configValues === null) {
            return false;
        }
        return isset($this->configValues[$className]) && isset($this->configValues[$className]["code"]);
    }

    /**
     * @param string $className
     * @return string|null
     */
    public function getCodeForClassName(string $className): ?string
    {
        if (!$this->isDefined($className)) {
            return null;
        }
        $classConfig = $this->configValues[$className];

        return $this->configValues[$className]["code"];
    }

}