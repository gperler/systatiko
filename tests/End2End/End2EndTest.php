<?php

declare(strict_types = 1);

namespace SystatikoTest\End2End;


use Systatiko\Generator\Generator;
use Systatiko\Logger\CodeceptionLogger;

class End2EndTest extends \PHPUnit_Framework_TestCase
{


    const GENERATOR_CONFIG = "./tests/End2End/Asset/generator.test.config.json";


    protected function setUp()
    {

        $generator = new Generator();

        $generator->setLogger(new CodeceptionLogger());
        $generator->start(self::GENERATOR_CONFIG);
    }

}