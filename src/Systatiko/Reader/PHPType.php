<?php

declare(strict_types=1);

namespace Systatiko\Reader;

class PHPType
{
    const BOOL = "bool";

    const INT = "int";

    const FLOAT = "float";

    const STRING = "string";

    const ARRAY = "array";

    const CALLABLE = "callable";


    const SCALAR_TYPE_LIST = [
        self::BOOL,
        self::INT,
        self::FLOAT,
        self::STRING,
        self::ARRAY,
        self::CALLABLE
    ];

    public function __construct(string $name = null, array $registeredClassNameList = [])
    {

    }
}