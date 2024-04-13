<?php

declare(strict_types=1);

namespace SystatikoTest\Functional\Asset;

use Civis\Common\File as Test;
use Systatiko\Annotation\Event;
use Systatiko\Reader\PHPClass;

/**
 *
 */
#[Event(namespace: '\test\namespace',name: "")]
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