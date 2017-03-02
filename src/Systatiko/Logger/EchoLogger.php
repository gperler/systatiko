<?php

namespace Systatiko\Logger;

use Psr\Log\LoggerInterface;

class EchoLogger implements LoggerInterface
{
    public function emergency($message, array $context = array())
    {
        echo "[emergency] " . $message . PHP_EOL;
    }

    public function alert($message, array $context = array())
    {
        echo "[alert] " . $message . PHP_EOL;
    }

    public function critical($message, array $context = array())
    {
        echo "[critical] " . $message . PHP_EOL;
    }

    public function error($message, array $context = array())
    {
        echo "[error] " . $message . PHP_EOL;
    }

    public function warning($message, array $context = array())
    {
        echo "[warning] " . $message . PHP_EOL;
    }

    public function notice($message, array $context = array())
    {
        echo "[notice] " . $message . PHP_EOL;
    }

    public function info($message, array $context = array())
    {
        echo "[info] " . $message . PHP_EOL;
    }

    public function debug($message, array $context = array())
    {
        echo "[debug] " . $message . PHP_EOL;
    }

    public function log($level, $message, array $context = array())
    {
        echo "[$level] " . $message . PHP_EOL;
    }

}