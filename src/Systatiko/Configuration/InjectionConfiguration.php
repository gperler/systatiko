<?php

declare(strict_types=1);

namespace Systatiko\Configuration;

class InjectionConfiguration
{

    /**
     * InjectionConfiguration constructor.
     */
    public function __construct(private ?array $configValues = null)
    {
    }

    /**
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