<?php

declare(strict_types = 1);

namespace Systatiko\Reader;

use Civis\Common\StringUtil;

class PHPClassName
{

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $classShortName;

    /**
     * @var string
     */
    private $as;

    /**
     * @var string
     */
    private $namespaceName;

    /**
     * PHPClassName constructor.
     *
     * @param string $className
     * @param string|null $as
     */
    public function __construct(string $className, string $as = null)
    {
        $this->as = $as;
        $this->className = $className;
        $this->namespaceName = StringUtil::getStartBeforeLast($className, "\\");
        $this->classShortName =  ($as !== null) ? $as : StringUtil::getEndAfterLast($className, "\\");
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getClassShortName(): string
    {
        return $this->classShortName;
    }

    /**
     * @return string
     */
    public function getAs()
    {
        return $this->as;
    }

    /**
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->namespaceName;
    }

}
