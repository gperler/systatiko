<?php

declare(strict_types=1);

namespace Systatiko\Reader;

class PHPType
{
    public const BOOL = "bool";

    public const INT = "int";

    public const FLOAT = "float";

    public const STRING = "string";

    public const ARRAY = "array";

    public const CALLABLE = "callable";


    public const SCALAR_TYPE_LIST = [
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