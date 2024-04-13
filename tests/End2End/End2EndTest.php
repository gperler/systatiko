<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End;

use Civis\Common\File;
use Psr\Log\NullLogger;
use Systatiko\Generator\Generator;
use Systatiko\Logger\CodeceptionLogger;

class End2EndTest extends \PHPUnit_Framework_TestCase
{

    const GEN_DIR = "./tests/SystatikoTest";
    const GENERATOR_CONFIG = "./tests/End2End/Asset/generator.test.config.json";

    protected function setUp(): void
    {
        parent::setUp();
        $genDir = new File(self::GEN_DIR);
        $genDir->deleteRecursively();

        $generator = new Generator();
        //$generator->setLogger(new CodeceptionLogger());
        $generator->setLogger(new NullLogger());
        $generator->start(self::GENERATOR_CONFIG);
    }

}