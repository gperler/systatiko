<?php

namespace Systatiko\Contract;

interface FacadeLocatorContract
{

    /**
     * @return string
     */
    public function getContext();

    /**
     * @param string $context
     *
     * @return void
     */
    public function setContext($context);

    /**
     * @param string $componentName
     *
     * @return array|null
     */
    public function getComponentConfiguration(string $componentName);
}