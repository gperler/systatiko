<?php

namespace Systatiko\Configuration;

use Civis\Common\ArrayUtil;
use Civis\Common\File;
use Systatiko\Reader\PHPClassName;

class GeneratorConfiguration
{

    const EXCEPTION_FILE_DOES_NOT_EXIST = "Configuration file '%s' does not exist.";

    const EXCEPTION_CONFIGURATION_INVALID_OR_EMPTY = "Configuration file '%s' either empty or invalid json.";

    const EXCEPTION_BACKBONE_CLASS_NOT_DEFINED = "Configuration file '%s' does not contain backbone.className";

    const EXCEPTION_NO_INCLUDE_DIR = "Configuration file '%s' parameter includeDir must be defined as an array with at least one directory";

    const BACKBONE = "backbone";

    const BACKBONE_CLASS = "className";

    const BACKBONE_EXTENDS = "extendsClassName";

    const BACKBONE_EXTENDS_DEFAULT = "Systatiko\\Runtime\\BackboneBase";

    const INCLUDE_DIR = "includeDir";

    const LOGGER = "logger";

    const TARGET_DIR = "targetDir";

    /**
     * @var File
     */
    protected $configFile;

    /**
     * @var array
     */
    protected $configurationValueList;

    /**
     * @var PHPClassName
     */
    protected $backboneClassName;

    /**
     * @var PHPClassName
     */
    protected $extendsClassName;

    /**
     * GeneratorConfiguration constructor.
     *
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->configFile = new File($fileName);
        $this->loadConfigValues();
        $this->parseBackboneConfig();
    }

    protected function loadConfigValues()
    {
        $fileName = $this->configFile->getAbsoluteFileName();
        if (!$this->configFile->exists()) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_FILE_DOES_NOT_EXIST, $fileName));
        }

        $this->configurationValueList = $this->configFile->loadAsJSONArray();
    }

    protected function parseConfigFile()
    {
        $this->parseBackboneConfig();
        $includeDir = $this->getIncludeDirectories();
        if ($includeDir === null || !is_array($includeDir) || sizeof($includeDir) === 0) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_NO_INCLUDE_DIR, $this->configFile->getAbsoluteFileName()));
        }

    }

    /**
     * @throws ConfigurationException
     */
    protected function parseBackboneConfig()
    {
        $backboneClassName = $this->getSubConfigValue(self::BACKBONE, self::BACKBONE_CLASS);
        if (!$backboneClassName) {
            $exception = sprintf(self::EXCEPTION_BACKBONE_CLASS_NOT_DEFINED, $this->configFile->getAbsoluteFileName());
            throw new ConfigurationException($exception);
        }
        $this->backboneClassName = new PHPClassName($backboneClassName);

        $extendsClassName = $this->getSubConfigValue(self::BACKBONE, self::BACKBONE_EXTENDS, self::BACKBONE_EXTENDS_DEFAULT);
        $this->extendsClassName = new PHPClassName($extendsClassName);
    }

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return null|string
     */
    protected function getConfigValue(string $key, string $default = null)
    {
        $value = ArrayUtil::getFromArray($this->configurationValueList, $key);
        return $value ?: $default;
    }

    /**
     * @param string $key
     * @param string $subkey
     * @param string|null $default
     *
     * @return null|string
     */
    protected function getSubConfigValue(string $key, string $subkey, string $default = null)
    {
        $subConfig = $this->getConfigValue($key);
        $value = ArrayUtil::getFromArray($subConfig, $subkey);
        return $value ?: $default;
    }

    /**
     * @return null|string
     */
    public function getIncludeDirectories()
    {
        return $this->getConfigValue(self::INCLUDE_DIR);
    }

    /**
     * @return null|string
     */
    public function getLogger()
    {
        return $this->getConfigValue(self::LOGGER);
    }

    /**
     * @return null|string
     */
    public function getTargetDir()
    {
        return $this->getConfigValue(self::TARGET_DIR);
    }

    /**
     * @return string
     */
    public function getBackboneClassName()
    {
        return $this->backboneClassName->getClassName();
    }

    /**
     * @return string
     */
    public function getBackboneClassShortName()
    {
        return $this->backboneClassName->getClassShortName();
    }

    /**
     * @return string
     */
    public function getBackboneExtendsClassName()
    {
        return $this->extendsClassName->getClassName();
    }
}