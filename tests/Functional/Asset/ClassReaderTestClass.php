<?php

declare(strict_types = 1);

namespace SystatikoTest\Functional\Asset;

use Systatiko\Reader\PHPClass;
use Civis\Common\File as Test;

/**
 * @Event(namespace="\test\namespace")
 *
 */
class ClassReaderTestClass extends \DateTime implements \Serializable
{
    /**
     * ClassReaderTest constructor.
     *
     * @param string $time
     * @param \DateTimeZone $timezone
     */
    public function __construct($time, \DateTimeZone $timezone)
    {
        parent::__construct($time, $timezone);
        new PHPClass(new Test(""), "");
    }

    public function serialize()
    {
    }

    public function unserialize($serialized)
    {
    }

}