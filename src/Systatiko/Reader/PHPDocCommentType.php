<?php

declare(strict_types=1);

namespace Systatiko\Reader;

use Civis\Common\StringUtil;

class PHPDocCommentType
{

    /**
     * @var string
     */
    private $original;

    /**
     * @var bool
     */
    private $isArray;

    /**
     * @var bool
     */
    private $isVoid;

    /**
     * @var bool
     */
    private $canBeNull;

    /**
     * @var  PHPClassName
     */
    private $className;

    /**
     * @var string
     */
    private $typeName;

    /**
     * PHPDocBlockType constructor.
     *
     * @param string|null $name
     */
    public function __construct(string $name, private readonly PHPMethod $method)
    {
        $this->original = trim($name);
        $this->canBeNull = false;
        $this->isArray = false;
        $this->isVoid = ($this->original === 'void');
        $this->extract();
    }

    /**
     *
     */
    private function extract()
    {
        $typeList = explode("|", $this->original);

        foreach ($typeList as $type) {
            if ($type === 'null') {
                $this->canBeNull = true;
                continue;
            }
            $this->analyzeType($type);
        }
    }

    private function analyzeType(string $type)
    {
        $this->isArray = StringUtil::endsWith($type, "[]");
        $type = trim($type, '[]');
        $this->className = $this->method->getClassNameForShortName($type);
        $this->typeName = ($this->className === null) ? $type : null;
    }

    /**
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }

    /**
     * @return boolean
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }

    /**
     * @return boolean
     */
    public function canBeNull(): bool
    {
        return $this->canBeNull;
    }

    /**
     * @return PHPClassName|null
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return bool
     */
    public function isVoid(): bool
    {
        return $this->isVoid;
    }

    /**
     * @return null|string
     */
    public function getFullyQualifiedName(): ?string
    {
        if ($this->isVoid()) {
            return null;
        }

        $arrayAddon = $this->isArray() ? '[]' : '';

        if ($this->typeName !== null) {
            return $this->typeName . $arrayAddon;
        }

        if ($this->className->getAs() !== null) {
            return $this->className->getAs() . $arrayAddon;
        }

        return $this->className->getClassName() . $arrayAddon;
    }

    /**
     * @return bool
     */
    public function isAsClassName(): bool
    {
        return $this->className !== null && $this->className->getAs() !== null;
    }

}