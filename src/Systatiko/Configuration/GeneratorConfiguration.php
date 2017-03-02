<?php

namespace Systatiko\Configuration;



use Civis\Common\File;
use Civis\Common\ArrayUtil;

class GeneratorConfiguration
{

    const DEFAULT_FACTORYLOCATOR_BASE_SHORT_NAME = "FacadeLocatorBase";

    const DEFAULT_FACTORYLOCATOR_NAMESPACE_NAME = "Systatiko\\Runtime";

    const EXCEPTION_FILE_DOES_NOT_EXIST = "Configuration file '%s' does not exist.";

    const EXCEPTION_CONFIGURATION_INVALID_OR_EMPTY = "Configuration file '%s' either empty or invalid json.";

    const EXCEPTION_FL_NOT_DEFINED = "Configuration file '%s' does not contain facadeLocator.classShortName or facadeLocator.namespaceName";

    const EXCEPTION_NO_INCLUDE_DIR = "Configuration file '%s' parameter includeDir must be defined as an array with at least one directory";

    const FACTORY_LOCATOR = "facadeLocator";

    const FL_CLASS_SHORT_NAME = "classShortName";

    const FL_NAMESPACE_NAME = "namespaceName";

    const FL_EXTENDS_SHORT_NAME = "extendsShortName";

    const FL_EXTENDS_NAMESPACE_NAME = "extendsNamespaceName";

    const INCLUDE_DIR = "includeDir";

    const LOGGER = "logger";

    const TARGET_DIR = "targetDir";

    /**
     * @var array
     */
    protected $configurationValueList;

    public function __construct($fileName)
    {
        $file = new File($fileName);
        $this->parseConfigFile($file);
    }

    /**
     * @param File $file
     *
     * @throws ConfigurationException
     */
    protected function parseConfigFile(File $file)
    {
        $filePath = $file->getAbsoluteFileName();
        if (!$file->exists()) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_FILE_DOES_NOT_EXIST, $filePath));
        }

        $this->configurationValueList = $file->loadAsJSONArray();

        if ($this->configurationValueList === null) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_CONFIGURATION_INVALID_OR_EMPTY, $filePath));
        }

        if ($this->getFacadeLocatorClassShortName() === null || $this->getFacadeLocatorNamespaceName() === null) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_FL_NOT_DEFINED, $filePath));
        }

        $includeDir = $this->getIncludeDirectories();
        if ($includeDir === null || !is_array($includeDir) || sizeof($includeDir) === 0) {
            throw new ConfigurationException(sprintf(self::EXCEPTION_NO_INCLUDE_DIR, $filePath));
        }

    }

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return null|string
     */
    protected function getConfigurationValue(string $key, string $default = null)
    {
        $value = ArrayUtil::getFromArray($this->configurationValueList, $key);
        if ($value !== null) {
            return $value;
        }
        return $default;
    }

    /**
     * @return null|string
     */
    protected function getFacadeLocator()
    {
        return $this->getConfigurationValue(self::FACTORY_LOCATOR);
    }

    /**
     * @return null|string
     */
    public function getFacadeLocatorClassShortName()
    {
        return trim(ArrayUtil::getFromArray($this->getFacadeLocator(), self::FL_CLASS_SHORT_NAME), "\\");
    }

    /**
     * @return null|string
     */
    public function getFacadeLocatorNamespaceName()
    {
        return trim(ArrayUtil::getFromArray($this->getFacadeLocator(), self::FL_NAMESPACE_NAME), "\\");
    }

    /**
     * @return string
     */
    public function getFacadeLocatorClassName()
    {
        return $this->getFacadeLocatorNamespaceName() . "\\" . $this->getFacadeLocatorClassShortName();
    }

    /**
     * @return string
     */
    public function getFacadeExtendsShortName() : string
    {
        $facadeLocator = $this->getFacadeLocator();
        $shortName = ArrayUtil::getFromArray($facadeLocator, self::FL_EXTENDS_SHORT_NAME);
        if (!empty($shortName)) {
            return $shortName;
        }
        return self::DEFAULT_FACTORYLOCATOR_BASE_SHORT_NAME;
    }

    /**
     * @return string
     */
    public function getFacadeExtendsNamespaceName() : string
    {
        $facadeLocator = $this->getFacadeLocator();
        $shortName = ArrayUtil::getFromArray($facadeLocator, self::FL_EXTENDS_NAMESPACE_NAME);
        if (!empty($shortName)) {
            return $shortName;
        }
        return self::DEFAULT_FACTORYLOCATOR_NAMESPACE_NAME;
    }

    /**
     * @return string
     */
    public function getFacadeExtendsClassName() : string
    {
        return $this->getFacadeExtendsNamespaceName() . "\\" . $this->getFacadeExtendsShortName();
    }

    /**
     * @return null|string
     */
    public function getIncludeDirectories()
    {
        return $this->getConfigurationValue(self::INCLUDE_DIR);
    }

    /**
     * @return null|string
     */
    public function getLogger()
    {
        return $this->getConfigurationValue(self::LOGGER);
    }

    /**
     * @return null|string
     */
    public function getTargetDir()
    {
        return $this->getConfigurationValue(self::TARGET_DIR);
    }
}