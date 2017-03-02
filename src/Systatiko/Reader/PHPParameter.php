<?php

namespace Systatiko\Reader;

use Civis\Common\ArrayUtil;
use Civis\Common\StringUtil;

class PHPParameter
{

    /**
     * @var PHPMethod
     */
    protected $phpMethod;

    /**
     * @var PHPDocCommentType
     */
    protected $docBlockType;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var PHPClassName
     */
    protected $className;

    /**
     * PHPParameter constructor.
     *
     * @param PHPMethod $method
     * @param string|null $signaturePart
     */
    public function __construct(PHPMethod $method, string $signaturePart)
    {
        $this->phpMethod = $method;
        $this->readSignaturePart($signaturePart);
    }

    /**
     * @param string $signaturePart
     */
    protected function readSignaturePart(string $signaturePart)
    {
        $typeNameDefault = explode("=", trim($signaturePart));
        $this->extractTypeAndName(ArrayUtil::getFromArray($typeNameDefault, 0));
        $this->setDefault(ArrayUtil::getFromArray($typeNameDefault, 1));
        $this->extractDocBlockType();
    }

    /**
     * @param string $typeName
     */
    protected function extractTypeAndName(string $typeName)
    {
        $typeName = trim(preg_replace('/ +/', ' ', $typeName));
        $typeNameList = explode(" ", $typeName);

        if (sizeof($typeNameList) === 1) {
            $this->setName($typeNameList[0]);
            return;
        }
        $this->extractWithType($typeNameList);
    }

    /**
     * @param string[] $typeNameList
     */
    protected function extractWithType(array $typeNameList)
    {
        $this->setName($typeNameList[1]);

        $type = $typeNameList[0];
        $className = $this->phpMethod->getClassNameForShortName($type);

        if ($className !== null) {
            $this->setClassName($className);
            return;
        }
        $this->setType($type);
    }

    /**
     *
     */
    protected function extractDocBlockType()
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
    public function getSignatureSnippet() : string
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
        $this->name = str_replace('$', '', $name);
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
    public function isAsClassName() : bool
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