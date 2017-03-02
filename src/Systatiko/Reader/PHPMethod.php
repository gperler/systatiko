<?php

namespace Systatiko\Reader;

use Doctrine\Common\Annotations\SimpleAnnotationReader;

class PHPMethod
{
    /**
     * @var PHPClass
     */
    protected $phpClass;

    /**
     * @var \ReflectionMethod
     */
    protected $reflectionMethod;

    /**
     * @var PHPParameter[]
     */
    protected $parameterList;

    /**
     * @var PHPMethodReturnType
     */
    protected $methodReturnType;

    /**
     * PHPMethod constructor.
     *
     * @param PHPClass $phpClass
     * @param \ReflectionMethod $reflectionMethod
     */
    public function __construct(PHPClass $phpClass, \ReflectionMethod $reflectionMethod)
    {
        $this->phpClass = $phpClass;
        $this->reflectionMethod = $reflectionMethod;
        $this->parameterList = [];
        $this->extractParameterList();
        $this->extractMethodReturnType();
    }

    /**
     *
     */
    protected function extractParameterList()
    {
        // find signature
        $pattern = '/function .*?\((.*?)\)/';
        preg_match($pattern, $this->getMethodDefinition(), $matchList);

        if ($matchList[1] === "") {
            return;
        }

        foreach (explode(",", $matchList[1]) as $signatureElement) {
            $this->parameterList[] = new PHPParameter($this, $signatureElement);
        }
    }

    /**
     *
     */
    protected function extractMethodReturnType()
    {
        $this->methodReturnType = new PHPMethodReturnType($this);
    }

    /**
     * @return string
     */
    public function getMethodName() : string
    {
        return $this->reflectionMethod->getName();
    }

    /**
     * @return string
     */
    public function getDocComment()
    {
        return $this->reflectionMethod->getDocComment();
    }

    /**
     * @param string $annotationName
     *
     * @return null|mixed
     */
    public function getMethodAnnotation(string $annotationName)
    {
        $className = new PHPClassName($annotationName);
        new $annotationName();
        $reader = new SimpleAnnotationReader();
        $reader->addNamespace($className->getNamespaceName());
        return $reader->getMethodAnnotation($this->reflectionMethod, $annotationName);
    }

    /**
     * @return string
     */
    public function getMethodDefinition() : string
    {
        $modifier = $this->reflectionMethod->isPublic() ? "public" : "";
        $modifier = $this->reflectionMethod->isProtected() ? "protected" : $modifier;
        $modifier = $this->reflectionMethod->isPrivate() ? "private" : $modifier;

        $content = $this->phpClass->getClassContent();
        $pattern = "/($modifier function " . $this->getMethodName() . '\(.*?\).*?)\{/s';
        preg_match($pattern, $content, $matches);
        return trim($matches[1]);
    }

    /**
     * @return string
     */
    public function getInvocationSignature() : string
    {
        $parameterList = [];
        foreach ($this->getMethodParameterList() as $methodParameter) {
            $parameterList[] = '$' . $methodParameter->getName();
        }
        return implode(", ", $parameterList);
    }

    /**
     * @return PHPMethodReturnType
     */
    public function getMethodReturnType() : PHPMethodReturnType
    {
        return $this->methodReturnType;
    }

    /**
     * @return PHPParameter[]
     */
    public function getMethodParameterList() : array
    {
        return $this->parameterList;
    }

    /**
     * @param string|null $classShortName
     *
     * @return PHPClassName
     */
    public function getClassNameForShortName(string $classShortName = null)
    {
        return $this->phpClass->getClassNameForShortName($classShortName);
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->reflectionMethod->isPublic();
    }

}