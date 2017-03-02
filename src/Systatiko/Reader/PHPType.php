<?php

declare(strict_types = 1);

namespace Systatiko\Reader;

class PHPType
{

    protected $isScalar;

    protected $isMixed;

    protected $className;



    const SCALAR_TYPE_LIST = [
        "bool",
        "int",
        "float",
        "string",
        "array",
        "callable"
    ];

    public function __construct(string $name = null, array $registeredClassNameList = [])
    {

    }
}