<?php

namespace Systatiko\Reader;

use Civis\Common\ArrayUtil;
use Codeception\Util\Debug;
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
     * @var string[]
     */
    protected $exceptionList;

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
        $this->exceptionList = [];
        $this->extractParameterList();
        $this->extractMethodReturnType();
    }

    /**
     *
     */
    protected function extractParameterList()
    {
        // find signature
//        $pattern = '/function .*?\((.*?)\)/';
//        preg_match($pattern, $this->getMethodDefinition(), $matchList);
//
//        $parameterList = ArrayUtil::getFromArray($matchList, 1);
//        if ($parameterList === "" || $parameterList === null) {
//            return;
//        }

        $reflectionParameterList = $this->reflectionMethod->getParameters();

        foreach($reflectionParameterList as $reflectionParameter) {
            $this->parameterList[] = new PHPParameter($this, $reflectionParameter);
        }

//
//        foreach (explode(",", $parameterList) as $signatureElement) {
//            $this->parameterList[] = new PHPParameter($this, $signatureElement);
//        }
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
     * @return PHPClassName[]
     */
    public function getThrownExceptionList() : array {
        $docComment = $this->getDocComment();

        preg_match_all("/@throws ([\\a-zA-Z0-9_.-]*?)\s/", $docComment,$matches);

        foreach($matches[1] as $exception) {
            $exceptionClassName = $this->getClassNameForShortName($exception);
            $this->exceptionList[] = $exceptionClassName;
        }
        return $this->exceptionList;
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
        $methodDefinition = ArrayUtil::getFromArray($matches, 1);

        return trim($methodDefinition);
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