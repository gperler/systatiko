<?php

namespace Systatiko\Reader;

use Civis\Common\ArrayUtil;
use Civis\Common\StringUtil;

class PHPParameter
{

    /**
     * @var PHPMethod
     */
    private $phpMethod;

    /**
     * @var PHPDocCommentType
     */
    private $docBlockType;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $default;

    /**
     * @var PHPClassName
     */
    private $className;


    /**
     * @var bool
     */
    private $allowsNull;

    /**
     * PHPParameter constructor.
     * @param PHPMethod $method
     * @param \ReflectionParameter $reflectionParameter
     */
    public function __construct(PHPMethod $method, \ReflectionParameter $reflectionParameter)
    {
        $this->phpMethod = $method;
        $this->readSignaturePart($reflectionParameter);
    }

    /**
     * @param \ReflectionParameter $reflectionParameter
     */
    private function readSignaturePart(\ReflectionParameter $reflectionParameter)
    {
        $this->name = $reflectionParameter->getName();

        $this->extractType($reflectionParameter);
        $this->extractDefaultValue($reflectionParameter);
        $this->extractDocBlockType();
    }

    /**
     * @param \ReflectionParameter $reflectionParameter
     */
    private function extractType(\ReflectionParameter $reflectionParameter)
    {
        $type = $reflectionParameter->getType();
        if ($type === null) {
            return;
        }
        if ($type->isBuiltin()) {
            $this->type = $type->getName();
        } else {
            $this->className = new PHPClassName($type->getName());
        }
    }


    /**
     * @param \ReflectionParameter $reflectionParameter
     */
    private function extractDefaultValue(\ReflectionParameter $reflectionParameter)
    {
        $this->allowsNull = $reflectionParameter->allowsNull();

        if ($reflectionParameter->isDefaultValueAvailable()) {
            $this->default = $reflectionParameter->getDefaultValue();
        }

    }

    /**
     *
     */
    private function extractDocBlockType()
    {
        $pattern = '/.*?@param (.*?) \$' . $this->getName() . '.*?/';
        $docComment = $this->phpMethod->getDocComment();

        preg_match($pattern, $docComment, $matches);

        $docBlockType = ArrayUtil::getFromArray($matches, 1);
        if ($docBlockType === null) {
            return;
        }

        $this->docBlockType = new PHPDocCommentType($docBlockType, $this->phpMethod);
    }



    /**
     * @return string
     */
    public function getSignatureSnippet(): string
    {
        $default = ($this->default !== null) ? " = " . $this->default : "";

        if ($this->type === null && $this->className === null) {
            return '$' . $this->name . $default;
        }

        $signatureType = ($this->className !== null) ? $this->className->getClassShortName() : $this->type;

        return $signatureType . ' $' . $this->name . $default;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(string $type = null)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $default
     */
    public function setDefault(string $default = null)
    {
        $this->default = StringUtil::trimToNull($default);
    }

    /**
     * @return bool
     */
    public function isAllowsNull(): bool
    {
        return $this->allowsNull;
    }




    /**
     * @return PHPClassName
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param PHPClassName|null $className
     */
    public function setClassName(PHPClassName $className = null)
    {
        $this->className = $className;
    }

    /**
     * @return PHPDocCommentType
     */
    public function getDocBlockType()
    {
        return $this->docBlockType;
    }

    /**
     * @return null|string
     */
    public function getFullyQualifiedName()
    {
        if ($this->docBlockType !== null && $this->docBlockType->getFullyQualifiedName() !== 'mixed') {
            return $this->docBlockType->getFullyQualifiedName();
        }

        if ($this->type !== null) {
            return $this->type;
        }

        if ($this->type === null) {
            return null;
        }

        if ($this->className->getAs() !== null) {
            return $this->className->getAs();
        }

        return $this->className->getClassName();
    }

    /**
     * @return bool
     */
    public function isAsClassName(): bool
    {
        if ($this->docBlockType !== null) {
            return $this->docBlockType->isAsClassName();
        }
        if ($this->className !== null && $this->className->getAs() !== null) {
            return true;
        }
        return false;
    }

    /**
     * @return null|PHPClassName
     */
    public function getDocBlockOrTypeClassName()
    {
        if ($this->docBlockType !== null) {
            return $this->docBlockType->getClassName();
        }
        return $this->getClassName();
    }

}