<?php

namespace Systatiko\Runtime;

use Systatiko\Contract\FacadeLocatorContract;
use Civis\Common\ArrayUtil;

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
     * @param array $configurationValueList
     */
    public function setConfigurationValueList($configurationValueList)
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