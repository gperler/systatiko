<?php

namespace Systatiko\Reader;

use Civis\Common\ArrayUtil;
use Civis\Common\StringUtil;
use ReflectionParameter;

class PHPParameter
{

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
     * @var bool
     */
    private $hasDefault;


    /**
     * @var string
     */
    private $constantDefaultParameter;


    /**
     * PHPParameter constructor.
     */
    public function __construct(private readonly PHPMethod $phpMethod, ReflectionParameter $reflectionParameter)
    {
        $this->readSignaturePart($reflectionParameter);
    }


    /**
     * @throws \ReflectionException
     */
    private function readSignaturePart(ReflectionParameter $reflectionParameter): void
    {
        $this->name = $reflectionParameter->getName();

        $this->extractType($reflectionParameter);
        $this->extractDefaultValue($reflectionParameter);
        $this->extractDocBlockType();
    }


    private function extractType(ReflectionParameter $reflectionParameter): void
    {
        $type = $reflectionParameter->getType();
        if ($type === null) {
            return;
        }

        if ($type->isBuiltin()) {
            $this->type = $type->getName();
        } else {
            $shortName = $this->phpMethod->getShortNameForClassName($type->getName());
            $this->className = new PHPClassName($type->getName(), $shortName);
        }
    }


    /**
     * @throws \ReflectionException
     */
    private function extractDefaultValue(ReflectionParameter $reflectionParameter): void
    {
        $this->allowsNull = $reflectionParameter->allowsNull();

        if ($reflectionParameter->isDefaultValueAvailable()) {
            $this->default = $reflectionParameter->getDefaultValue();
            $this->hasDefault = true;

            $constant = $reflectionParameter->getDefaultValueConstantName();
            if ($constant !== null) {
                $this->constantDefaultParameter = str_replace("self", $this->phpMethod->getClassName(), $constant);
            }
        }
    }


    /**
     *
     */
    private function extractDocBlockType(): void
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
        $default = $this->getDefaultForSignature();

        if ($this->type === null && $this->className === null) {
            return '$' . $this->name . $default;
        }

        $signatureType = ($this->className !== null) ? $this->className->getClassShortName() : $this->type;

        return $signatureType . ' $' . $this->name . $default;
    }


    /**
     * @return string
     */
    private function getDefaultForSignature(): string
    {
        $nitriaDefault = $this->getNitriaDefault();
        if ($nitriaDefault === null) {
            return "";
        }
        return " = " . $nitriaDefault;
    }


    /**
     * @return null|string
     */
    public function getNitriaDefault(): ?string
    {
        if (!$this->hasDefault) {
            return null;
        }
        if ($this->default === null || $this->constantDefaultParameter !== null) {
            return "null";
        }
        if ($this->type === PHPType::STRING) {
            return '"' . $this->default . '"';
        }
        if ($this->default === true) {
            return "true";
        }
        if ($this->default === false) {
            return "false";
        }
        if ($this->type === PHPType::ARRAY) {
            return json_encode($this->default);
        }

        return $this->default;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


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