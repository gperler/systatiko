<?php

namespace Systatiko\Logger;

use Codeception\Util\Debug;
use Psr\Log\LoggerInterface;

class CodeceptionLogger implements LoggerInterface
{
    public function emergency($message, array $context = array())
    {
        Debug::debug("[emergency] " . $message);
    }

    public function alert($message, array $context = array())
    {
        Debug::debug("[alert] " . $message);
    }

    public function critical($message, array $context = array())
    {
        Debug::debug("[critical] " . $message);
    }

    public function error($message, array $context = array())
    {
        Debug::debug("[error] " . $message);
    }

    public function warning($message, array $context = array())
    {
        Debug::debug("[warning] " . $message);
    }

    public function notice($message, array $context = array())
    {
        Debug::debug("[notice] " . $message);
    }

    public function info($message, array $context = array())
    {
        Debug::debug("[info] " . $message);
    }

    public function debug($message, array $context = array())
    {
        Debug::debug("[debug] " . $message);
    }

    public function log($level, $message, array $context = array())
    {
        Debug::debug("[log] " . $message);
    }

}