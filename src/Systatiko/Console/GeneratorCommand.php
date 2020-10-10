<?php

namespace Systatiko\Console;

use Civis\Common\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Systatiko\Generator\Generator;
use Systatiko\Logger\SymphonyConsoleLogger;

class GeneratorCommand extends Command
{

    const DEFAULT_CONFIG = "generator.config.json";

    const OPTION_CONFIG_FILE = "configFile";

    const NO_CONFIG_FILE = "<error>No config file found. use --configFile option </error>";

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var float
     */
    protected $startTime;

    protected function configure()
    {
        $this->setName("gen");
        $this->setDescription("Generates component factories, component facades and facade locator");

        $this->addOption(GeneratorCommand::OPTION_CONFIG_FILE, null, InputOption::VALUE_OPTIONAL, "Path to config file to use.");

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;

        $this->startTimer();

        $configurationFileName = $this->getConfigurationFileName();

        if ($configurationFileName === null) {
            $output->writeln(self::NO_CONFIG_FILE);
            return -1;
        }

        $output->writeln("I'm using config file " . $configurationFileName);

        $generator = new Generator();
        $generator->setLogger(new SymphonyConsoleLogger($output));
        $generator->start($configurationFileName);

        $this->endTimer();

        return 0;
    }

    /**
     * @return string|null
     */
    protected function getConfigurationFileName()
    {
        $configFile = $this->input->getOption(GeneratorCommand::OPTION_CONFIG_FILE);

        if ($configFile !== null) {
            return $configFile;
        }

        $file = new File(getcwd());
        $configFile = $file->findFirstOccurenceOfFile(self::DEFAULT_CONFIG);
        if ($configFile !== null) {
            return $configFile->getAbsoluteFileName();
        }
        return null;

    }

    /**
     * @return void
     */
    protected function startTimer()
    {
        $this->startTime = microtime(true);
    }

    /**
     * @return void
     */
    protected function endTimer()
    {
        $delta = (microtime(true) - $this->startTime) * 1000;
        $this->output->writeln(sprintf("Generation complete in %0.2fms", $delta));
    }
}