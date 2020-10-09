<?php

declare(strict_types=1);

namespace Systatiko;

class StopWatch
{
    private static $startTime;


    /**
     *
     */
    public static function start()
    {
        self::$startTime = microtime(true);
    }


    /**
     * @param string $text
     * @param int $indent
     */
    public static function echoTime(string $text, int $indent = 0)
    {
        $indentText = '';
        if ($indent !== 0) {
            for ($index = 0; $index < $indent; $index++) {
                $indentText .= '   ';
            }
            $indentText .= '# ';
        }

        $delta = (microtime(true) - self::$startTime) * 1000;
        echo $indentText . $text . ' > ' . $delta . PHP_EOL;
    }
}