<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End;

use Civis\Common\ArrayUtil;
use Civis\Common\File;
use Systatiko\Generator\Generator;
use Systatiko\Logger\CodeceptionLogger;
use SystatikoTest\End2End\Asset\Generated\FacadeLocator;

class End2EndTest extends \PHPUnit_Framework_TestCase
{

    const GEN_DIR = "./tests/SystatikoTest";
    const GENERATOR_CONFIG = "./tests/End2End/Asset/generator.test.config.json";

    protected function setUp()
    {
        parent::setUp();
        $genDir = new File(self::GEN_DIR);
        $genDir->deleteRecursively();

        $generator = new Generator();
        $generator->setLogger(new CodeceptionLogger());
        $generator->start(self::GENERATOR_CONFIG);
    }

    /**
     *
     */
    public function testFacadeLocator()
    {
        $locator = FacadeLocator::getInstance();
        $this->assertNotNull($locator);
        $this->assertInstanceOf('SystatikoTest\End2End\Asset\Generated\FacadeLocator', $locator);
    }

    /**
     *
     */
    public function testConfigFile()
    {
        $locator = FacadeLocator::getInstance(__DIR__ . DIRECTORY_SEPARATOR . "Asset" . DIRECTORY_SEPARATOR . "facade.locator.config.json");
        $config = $locator->getComponentConfiguration("Common");

        $this->assertNotNull($config);
        $this->assertTrue(is_array($config));
        $this->assertSame("config", ArrayUtil::getFromArray($config, "x"));
    }

    /**
     *
     */
    public function testContext()
    {
        $locator = FacadeLocator::getInstance();
        $this->assertNull($locator->getContext());
        $locator->setContext("C1");
        $this->assertSame("C1", $locator->getContext());
    }



}