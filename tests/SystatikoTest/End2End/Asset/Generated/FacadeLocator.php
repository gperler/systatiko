<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End\Asset\Generated;

use Civis\Common\File;
use SystatikoTest\End2End\Asset\FacadeLocatorProject;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Facade;
use SystatikoTest\End2End\Asset\Generated\Component1\Component1Factory;
use SystatikoTest\End2End\Asset\Generated\Component2\Component2Facade;
use SystatikoTest\End2End\Asset\Generated\Component2\Component2Factory;

class FacadeLocator extends FacadeLocatorProject
{

    /**
     * @var FacadeLocator
     */
    protected static $instance;

    /**
     * @param string|null $configFileName
     * 
     * @return FacadeLocator
     */
    public static function getInstance(string $configFileName = null) : FacadeLocator
    {
        if (self::$instance === null) {
            self::$instance = new FacadeLocator();
        }
        if ($configFileName !== null) {
            self::$instance->setConfigurationFile(new File($configFileName));
        }
        return self::$instance;
    }

    /**
     * @var Component1Factory
     */
    protected $component1Factory;

    /**
     * @var Component2Factory
     */
    protected $component2Factory;

    /**
     * 
     * @return Component1Facade
     */
    public function getComponent1Facade() : Component1Facade
    {
        return $this->getComponent1Factory()->getComponent1Facade();
    }

    /**
     * 
     * @return Component1Factory
     */
    public function getComponent1Factory() : Component1Factory
    {
        if ($this->component1Factory === null) {
            $this->component1Factory = new Component1Factory($this); 
        }
        return $this->component1Factory;
    }

    /**
     * 
     * @return Component2Facade
     */
    public function getComponent2Facade() : Component2Facade
    {
        return $this->getComponent2Factory()->getComponent2Facade();
    }

    /**
     * 
     * @return Component2Factory
     */
    public function getComponent2Factory() : Component2Factory
    {
        if ($this->component2Factory === null) {
            $this->component2Factory = new Component2Factory($this); 
        }
        return $this->component2Factory;
    }
}