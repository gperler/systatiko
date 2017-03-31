<?php

namespace Systatiko\Runtime;

use Civis\Common\ArrayUtil;
use Civis\Common\File;
use Systatiko\Contract\AsynchronousEvent;
use Systatiko\Contract\AsynchronousEventHandler;
use Systatiko\Contract\BackboneContract;
use Systatiko\Contract\SynchronousEvent;
use Systatiko\Contract\SynchronousEventHandler;

abstract class BackboneBase implements BackboneContract
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
     * @var AsynchronousEventHandler[]
     */
    protected $asynchronousEventHandlerList;

    /**
     * @var SynchronousEventHandler[]
     */
    protected $synchronousEventHandlerList;

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

    /**
     * @param AsynchronousEvent $event
     */
    public function dispatchOutboundAsynchronousEvent(AsynchronousEvent $event)
    {
        foreach ($this->asynchronousEventHandlerList as $handler) {
            $handler->handleEvent($event);
        }
    }

    /**
     * @param SynchronousEvent $event
     */
    public function dispatchSynchronousEvent(SynchronousEvent $event)
    {
        foreach ($this->synchronousEventHandlerList as $handler) {
            $handler->handleEvent($event);
        }
    }

    /**
     * @param AsynchronousEventHandler $handler
     */
    public function addOutboundAsynchronousEventHandler(AsynchronousEventHandler $handler)
    {
        $this->asynchronousEventHandlerList[] = $handler;
    }

    /**
     * @param SynchronousEventHandler $handler
     */
    public function addSynchronousEventHandler(SynchronousEventHandler $handler)
    {
        $this->synchronousEventHandlerList[] = $handler;
    }

}