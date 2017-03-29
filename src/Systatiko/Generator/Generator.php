<?php

namespace Systatiko\Generator;


use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Systatiko\Configuration\ConfigurationException;
use Systatiko\Configuration\GeneratorConfiguration;
use Systatiko\Logger\EchoLogger;
use Systatiko\Model\Project;
use Systatiko\Reader\PHPClassScanner;

class Generator implements LoggerAwareInterface
{

    /**
     * @var GeneratorConfiguration
     */
    protected $generatorConfiguration;

    /**
     * @var PHPClassScanner
     */
    protected $scanner;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Project
     */
    protected $project;

    /**
     * Generator constructor.
     */
    public function __construct()
    {
        $this->scanner = new PHPClassScanner();
        $this->project = new Project();
        $this->setLogger(new EchoLogger());
    }

    /**
     * @param string $configFile
     */
    public function start(string $configFile)
    {
        try {
            $this->generatorConfiguration = new GeneratorConfiguration($configFile);
        } catch (ConfigurationException $e) {
            $this->logger->error($e->getMessage());
            return;
        }

        $this->scanPHPFiles();

        $this->analyze();

        $this->generate();

        $this->logSummary();

    }

    protected function scanPHPFiles()
    {
        $includeDirectoryList = $this->generatorConfiguration->getIncludeDirectories();
        foreach ($includeDirectoryList as $includeDirectory) {
            $success = $this->scanner->addBaseDir($includeDirectory);

            if (!$success) {
                $this->logger->warning("Directory " . $includeDirectory . " does not exist or is not a directory. Skipping.");
            }
        }
    }

    /**
     *
     */
    protected function analyze()
    {
        $phpFileList = $this->scanner->getPHPClassList();
        $this->project->addPHPClassList($phpFileList);
        $this->project->analyze($this->generatorConfiguration->getBackboneExtendsClassName());
    }

    /**
     *
     */
    protected function generate()
    {
        $errorCount = $this->project->getErrorCount();
        if ($this->project->getErrorCount() !== 0) {
            $this->logger->error("Found $errorCount error(s) skipped generation");
            return;
        }
        $this->generateComponentFactory();
        $this->generateComponentFacade();
        $this->generateBackbone();
    }

    /**
     *
     */
    protected function generateComponentFactory()
    {
        foreach ($this->project->getComponentFactoryList() as $factory) {
            $fg = new ComponentFactoryGenerator($this->project, $factory);
            $fg->generate($this->generatorConfiguration);
        }
    }

    /**
     *
     */
    protected function generateComponentFacade()
    {
        foreach ($this->project->getComponentFacadeList() as $facade) {
            $cfg = new ComponentFacadeGenerator($this->project, $facade);
            $cfg->generate($this->generatorConfiguration);
        }
    }

    /**
     *
     */
    protected function generateBackbone()
    {
        $flg = new BackboneGenerator($this->project->getComponentFacadeList(), $this->project->getComponentEventList());
        $flg->generate($this->generatorConfiguration);

    }

    protected function logSummary()
    {
        $componentFactoryCount = sizeof($this->project->getComponentFactoryList());
        $componentFacadeCount = sizeof($this->project->getComponentFacadeList());
        $this->logger->info("Generated Backbone ");
        $this->logger->info("Generated $componentFactoryCount ComponentFactory|ies");
        $this->logger->info("Generated $componentFacadeCount ComponentFacade(s)");
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->project->setLogger($logger);
        return null;
    }

}