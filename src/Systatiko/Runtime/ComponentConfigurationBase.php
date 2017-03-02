<?php

namespace Systatiko\Runtime;


use Systatiko\Contract\ComponentConfiguration;
use Civis\Common\ArrayUtil;

class ComponentConfigurationBase implements ComponentConfiguration
{

    protected $valueList;

    /**
     * @param array $valueList
     */
    public function setValueList($valueList)
    {
        $this->valueList = $valueList;
    }

    /**
     * @param string $key
     * @param string|null $default
     *
     * @return null|string
     */
    public function getConfigurationValue(string $key, string $default = null)
    {
        $value = ArrayUtil::getFromArray($this->valueList, $key);
        if ($value !== null) {
            return $value;
        }
        return $default;
    }

}