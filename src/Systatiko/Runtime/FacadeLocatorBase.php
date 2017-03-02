<?php

namespace Systatiko\Runtime;

use Civis\Common\ArrayUtil;
use Civis\Common\File;
use Systatiko\Contract\FacadeLocatorContract;

class FacadeLocatorBase implements FacadeLocatorContract
{

    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $configurationValueList;

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getConfigurationValueList()
    {
        return $this->configurationValueList;
    }

    /**
     * @param File $configFile
     */
    public function setConfigurationFile(File $configFile)
    {
        $this->setConfigurationValueList($configFile->loadAsJSONArray());
    }

    /**
     * @param array $configurationValueList
     */
    public function setConfigurationValueList(array $configurationValueList)
    {
        $this->configurationValueList = $configurationValueList;
    }

    /**
     * @param $componentName
     *
     * @return null|string
     */
    public function getComponentConfiguration(string $componentName)
    {
        return ArrayUtil::getFromArray($this->configurationValueList, $componentName);
    }

}