<?php

declare(strict_types=1);

namespace Systatiko\Reader;

class PHPMethodReturnType
{

    /**
     * @var string
     */
    private $signatureType;

    /**
     * @var PHPClassName
     */
    private $signatureTypeClassName;

    /**
     * @var PHPDocCommentType
     */
    private $docBlockType;

    /**
     * @var bool
     */
    private $canBeNull;


    /**
     * PHPMethodReturnType constructor.
     */
    public function __construct(private readonly PHPMethod $phpMethod)
    {
        $this->canBeNull = false;
        $this->extractSignatureReturnType();
        $this->extractDocBlockReturnType();
    }


    /**
     *
     */
    private function extractSignatureReturnType()
    {
        $reflectMethod = $this->phpMethod->getReflectMethod();

        $returnType = $reflectMethod->getReturnType();

        if ($returnType === null) {
            $this->canBeNull = true;
            return;
        }


        $this->canBeNull = $returnType->allowsNull();

        $this->signatureType = $returnType->getName();
        $this->signatureTypeClassName = $this->phpMethod->getClassNameForShortName($this->signatureType);
    }


    /**
     *
     */
    private function extractDocBlockReturnType()
    {
        $docBlockType = $this->phpMethod->getDocComment();
        if (!$docBlockType) {
            return;
        }
        $pattern = '/.*?@return (.*?)\s/';
        preg_match($pattern, $this->phpMethod->getDocComment(), $matches);

        if (sizeof($matches) === 0) {
            return;
        }
        $docBlockType = trim($matches[1]);
        $this->docBlockType = new PHPDocCommentType($docBlockType, $this->phpMethod);
    }


    /**
     * @return string
     */
    public function getSignatureType()
    {
        return $this->signatureType;
    }


    /**
     * @return PHPClassName
     */
    public function getSignatureTypeClassName(): PHPClassName
    {
        return $this->signatureTypeClassName;
    }


    /**
     * @return PHPDocCommentType
     */
    public function getDocBlockType()
    {
        return $this->docBlockType;
    }


    /**
     * @return boolean
     */
    public function canBeNull(): bool
    {
        return $this->canBeNull;
    }


    /**
     * @return null|string
     */
    public function getFullyQualifiedName()
    {
        if ($this->docBlockType !== null) {
            return $this->docBlockType->getFullyQualifiedName();
        }

        if ($this->signatureType !== null) {
            return $this->signatureType;
        }

        if ($this->signatureTypeClassName === null) {
            return null;
        }

        if ($this->signatureTypeClassName->getAs() !== null) {
            return $this->signatureTypeClassName->getAs();
        }

        return $this->signatureTypeClassName->getClassName();
    }


    /**
     * @return bool
     */
    public function isAsClassName(): bool
    {
        if ($this->docBlockType !== null) {
            return $this->docBlockType->isAsClassName();
        }
        if ($this->signatureTypeClassName !== null && $this->signatureTypeClassName->getAs() !== null) {
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
        return $this->signatureTypeClassName;
    }

}