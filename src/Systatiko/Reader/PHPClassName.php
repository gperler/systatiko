<?php

declare(strict_types = 1);

namespace Systatiko\Reader;

use Civis\Common\StringUtil;

class PHPClassName
{

    /**
     * @var string
     */
    private $classShortName;

    /**
     * @var string
     */
    private $namespaceName;

    /**
     * PHPClassName constructor.
     */
    public function __construct(private readonly string $className, private readonly ?string $as = null)
    {
        $this->namespaceName = StringUtil::getStartBeforeLast($this->className, "\\");
        $this->classShortName =  $this->as ?? StringUtil::getEndAfterLast($this->className, "\\");
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
