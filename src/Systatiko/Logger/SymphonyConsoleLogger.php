<?php

namespace Systatiko\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;
use Civis\Common\ArrayUtil;

class SymphonyConsoleLogger implements LoggerInterface
{

    /**
     * @var OutputInterface
     */
    protected $outputInterfase;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->outputInterfase = $output;
    }

    /**
     * @param int $loglevel
     * @param string $message
     * @param array $context
     *
     * @return string
     */
    private function compileMessage($loglevel, $message, array $context = [])
    {
        $prefix = "";
        switch ($loglevel) {
            case LogLevel::ERROR:
                $prefix = "[ERROR] ";
                break;
            case LogLevel::WARNING:
                $prefix = "[WARNING] ";
                break;
        }
        $code = ArrayUtil::getFromArray($context, "code");

        if ($code) {
            return $prefix . $message . " (" . $code . ")";
        }

        return $prefix . $message;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function emergency($message, array $context = [])
    {
        $message = $this->compileMessage(LogLevel::EMERGENCY, $message, $context);
        $this->outputInterfase->writeln('<error>' . $message . '</error>');
    }

    /**
     * Action must be taken immediately.
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function alert($message, array $context = [])
    {
        $message = $this->compileMessage(LogLevel::ALERT, $message, $context);
        $this->outputInterfase->writeln('<error>' . $message . '</error>');
    }

    /**
     * Critical conditions.
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function critical($message, array $context = [])
    {
        $message = $this->compileMessage(LogLevel::CRITICAL, $message, $context);
        $this->outputInterfase->writeln('<error>' . $message . '</error>');
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function error($message, array $context = [])
    {
        $message = $this->compileMessage(LogLevel::ERROR, $message, $context);
        $this->outputInterfase->writeln('<error>' . $message . '</error>');
    }

    /**
     * Exceptional occurrences that are not errors.
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function warning($message, array $context = [])
    {
        $message = $this->compileMessage(LogLevel::WARNING, $message, $context);
        $this->outputInterfase->writeln('<bg=yellow>' . $message . '</>');
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function notice($message, array $context = [])
    {
        if ($this->outputInterfase->getVerbosity() < OutputInterface::VERBOSITY_VERBOSE) {
            return;
        }
        $message = $this->compileMessage(LogLevel::NOTICE, $message, $context);
        $this->outputInterfase->writeln($message);
    }

    /**
     * Interesting events.
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function info($message, array $context = [])
    {
        $message = $this->compileMessage(LogLevel::INFO, $message, $context);
        $this->outputInterfase->writeln($message);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function debug($message, array $context = [])
    {
        if ($this->outputInterfase->getVerbosity() < OutputInterface::VERBOSITY_DEBUG) {
            return;
        }
        $message = $this->compileMessage(LogLevel::DEBUG, $message, $context);
        $this->outputInterfase->writeln($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param int $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                $this->emergency($message, $context);
                break;
            case LogLevel::DEBUG:
                $this->debug($message, $context);
                break;
            case LogLevel::ERROR:
                $this->error($message, $context);
                break;
            case LogLevel::ALERT:
                $this->alert($message, $context);
                break;
            case LogLevel::CRITICAL:
                $this->critical($message, $context);
                break;
            case LogLevel::INFO:
                $this->info($message, $context);
                break;
            case LogLevel::NOTICE:
                $this->notice($message, $context);
                break;
            case LogLevel::WARNING:
                $this->warning($message, $context);
                break;
        }

    }

}