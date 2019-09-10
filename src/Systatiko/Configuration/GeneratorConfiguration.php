<?php

namespace Systatiko\Configuration;

use Civis\Common\ArrayUtil;
use Civis\Common\File;
use Exception;
use ReflectionClass;
use ReflectionException;
use Systatiko\Contract\FacadeGeneratorExtension;
use Systatiko\Reader\PHPClassName;

class GeneratorConfiguration
{

    const EXCEPTION_FILE_DOES_NOT_EXIST = "Configuration file '%s' does not exist.";

    const EXCEPTION_CONFIGURATION_INVALID_OR_EMPTY = "Configuration file '%s' either empty or invalid json.";

    const EXCEPTION_BACKBONE_CLASS_NOT_DEFINED = "Configuration file '%s' does not contain backbone.className";

    const EXCEPTION_NO_INCLUDE_DIR = "Configuration file '%s' parameter includeDir must be defined as an array with at least one directory";

    const EXCEPTION_FACADE_GENERATOR_EXTENSION_NO_ARRAY = "'facadeGeneratorExtension' is not an array []";

    const EXCEPTION_FACADE_GENERATOR_EXTENSION_DOES_NOT_IMPLEMENT = "Facade generator extension '%s' does not implement '%s'";

    const EXCEPTION_NO_PSR4_PREFIX = "For PSR-4 a the psr4Prefix must be set";

    const FACADE_GENERATOR_EXTENSION_INTERFACE = 'Systatiko\Contract\FacadeGeneratorExtension';

    const BACKBONE = "backbone";

    const BACKBONE_CLASS = "className";

    const BACKBONE_EXTENDS = "extendsClassName";

    const BACKBONE_EXTENDS_DEFAULT = "Systatiko\\Runtime\\BackboneBase";

    const BACKBONE_DEPENDENCY_FILE = 'dependencyFile';

    const FACADE_GENERATOR_EXTENSION = "facadeGeneratorExtension";

    const INCLUDE_DIR = "includeDir";

    const LOGGER = "logger";

    const TARGET_DIR = "targetDir";


    const PSR_MODE = "psrMode";

    const PSR_MODE_PSR0 = 'psr0';

    const PSR_MODE_PSR4 = 'psr4';

    const PSR4_PREFIX = 'psr4Prefix';


    /**
     * @var File
     */
    private $configFile;

    /**
     * @var array
     */
    private $configurationValueList;

    /**
     * @var PHPClassName
     */
    private $backboneClassName;

    /**
     * @var PHPClassName
     */
    private $extendsClassName;

    /**
     * @var FacadeGeneratorExtension[]
     */
    private $facadeGeneratorExtension;

    /**
     * GeneratorConfiguration constructor.
     * @param string $fileName
     * @throws ConfigurationException
     * @throws ReflectionException
     */
    public function __construct(string $fileName)
    {
        $this->configFile = new File($fileName);
        $this->facadeGeneratorExtension = [];
        $this->loadConfigValues();
        $this->parseConfigFile();
    }

    /**
     * @throws ConfigurationException
     * @throws Exception
     */
    private function loadConfigValues()
    {
        $fileName = $this->configFile->getAbsoluteFileName();
        if (!$this->configFile->exists()) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_FILE_DOES_NOT_EXIST, $fileName));
        }

        $this->configurationValueList = $this->configFile->loadAsJSONArray();
    }

    /**
     * @throws ConfigurationException
     * @throws ReflectionException
     */
    private function parseConfigFile()
    {
        $this->parseBackboneConfig();
        $this->parseIncludeDirectoryConfig();
        $this->parsePSR4Prefix();
        $this->parseFacadeGeneratorExtensionConfig();
    }

    /**
     * @throws ConfigurationException
     */
    private function parseBackboneConfig()
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
     * @throws ConfigurationException
     */
    private function parseIncludeDirectoryConfig()
    {
        $includeDir = $this->getIncludeDirectories();
        if ($includeDir === null || !is_array($includeDir) || sizeof($includeDir) === 0) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_NO_INCLUDE_DIR, $this->configFile->getAbsoluteFileName()));
        }
    }

    /**
     * @throws ConfigurationException
     */
    private function parsePSR4Prefix()
    {
        if (!$this->isPSR4() || $this->getPSR4Prefix() !== null) {
            return;
        }
        throw new ConfigurationException(self::EXCEPTION_NO_PSR4_PREFIX);
    }

    /**
     * @throws ConfigurationException
     * @throws ReflectionException
     */
    private function parseFacadeGeneratorExtensionConfig()
    {
        $generatorExtensionClassList = ArrayUtil::getFromArray($this->configurationValueList, self::FACADE_GENERATOR_EXTENSION);
        if ($generatorExtensionClassList === null) {
            return;
        }

        if (!is_array($generatorExtensionClassList)) {
            throw new ConfigurationException(self::EXCEPTION_FACADE_GENERATOR_EXTENSION_NO_ARRAY);
        }

        foreach ($generatorExtensionClassList as $generatorExtensionClassName) {
            $reflect = new ReflectionClass($generatorExtensionClassName);

            if (!$reflect->implementsInterface(self::FACADE_GENERATOR_EXTENSION_INTERFACE)) {
                $message = sprintf(self::EXCEPTION_FACADE_GENERATOR_EXTENSION_DOES_NOT_IMPLEMENT, $generatorExtensionClassName, self::FACADE_GENERATOR_EXTENSION_INTERFACE);
                throw new ConfigurationException($message);
            }

            $this->facadeGeneratorExtension[] = $reflect->newInstance();
        }

    }

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return null|string|array
     */
    private function getConfigValue(string $key, string $default = null)
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
    private function getSubConfigValue(string $key, string $subkey, string $default = null)
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
    private function getPSRMode(): string
    {
        return $this->getConfigValue(self::PSR_MODE, self::PSR_MODE_PSR0);
    }

    /**
     * @return bool
     */
    public function isPSR0(): bool
    {
        return $this->getPSRMode() === self::PSR_MODE_PSR0;
    }

    /**
     * @return bool
     */
    public function isPSR4(): bool
    {
        return $this->getPSRMode() === self::PSR_MODE_PSR4;
    }

    /**
     * @return string
     */
    public function getPSR4Prefix(): ?string
    {
        return $this->getConfigValue(self::PSR4_PREFIX);
    }

    /**
     * @return null|string
     */
    public function getDependencyFile()
    {
        return $this->getConfigValue(self::BACKBONE_DEPENDENCY_FILE);
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

    /**
     * @return FacadeGeneratorExtension[]
     */
    public function getFacadeGeneratorExtension(): array
    {
        return $this->facadeGeneratorExtension;
    }

}