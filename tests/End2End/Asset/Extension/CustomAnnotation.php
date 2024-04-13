<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Extension;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
class CustomAnnotation
{


    public string $roleRequired;


    /**
     * @param string $roleRequired
     */
    public function __construct(string $roleRequired)
    {
        $this->roleRequired = $roleRequired;
    }

}