<?php

namespace Systatiko\Reader;

use Civis\Common\StringUtil;
use Codeception\Util\Debug;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use ReflectionMethod;
use Systatiko\Annotation\Factory;

class PHPMethod
{
    /**
     * @var PHPClass
     */
    private $phpClass;

    /**
     * @var ReflectionMethod
     */
    private $reflectionMethod;

    /**
     * @var PHPParameter[]
     */
    private $parameterList;

    /**
     * @var PHPMethodReturnType
     */
    private $methodReturnType;

    /**
     * @var string[]
     */
    private $exceptionList;


    /**
     * PHPMethod constructor.
     *
     * @param PHPClass $phpClass
     * @param ReflectionMethod $reflectionMethod
     */
    public function __construct(PHPClass $phpClass, ReflectionMethod $reflectionMethod)
    {
        $this->phpClass = $phpClass;
        $this->reflectionMethod = $reflectionMethod;
        $this->parameterList = [];
        $this->exceptionList = [];
        $this->extractParameterList();
        $this->extractMethodReturnType();
    }


    /**
     * @return ReflectionMethod
     */
    public function getReflectMethod(): ReflectionMethod
    {
        return $this->reflectionMethod;
    }


    /**
     *
     */
    private function extractParameterList()
    {
        $reflectionParameterList = $this->reflectionMethod->getParameters();

        foreach ($reflectionParameterList as $reflectionParameter) {
            $this->parameterList[] = new PHPParameter($this, $reflectionParameter);
        }
    }


    /**
     * @param string|null $className
     *
     * @return null|string
     */
    public function getShortNameForClassName(string $className = null): ?string
    {
        return $this->phpClass->getShortNameForClassName($className);
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->phpClass->getClassName();
    }


    /**
     *
     */
    private function extractMethodReturnType()
    {
        $this->methodReturnType = new PHPMethodReturnType($this);
    }


    /**
     * @return string
     */
    public function getMethodName(): string
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
    public function getMethodAnnotation(string $annotationName): mixed
    {
        $attributeList = $this->reflectionMethod->getAttributes();
        foreach($attributeList as $attribute) {
            if ($attribute->getName() === $annotationName) {
                if ($annotationName === Factory::class) {
                    Debug::debug($attribute->newInstance());
                }
                return $attribute->newInstance();
            }
        }
        return null;
    }


    /**
     * @return PHPClassName[]
     */
    public function getThrownExceptionList(): array
    {
        $docComment = $this->getDocComment();

        preg_match_all("/@throws ([\\a-zA-Z0-9_.-]*?)\s/", $docComment, $matches);

        foreach ($matches[1] as $exception) {
            if (StringUtil::startsWith($exception, "\\")) {
                $exceptionClassName = new PHPClassName($exception);
            } else {
                $exceptionClassName = $this->getClassNameForShortName($exception);
            }

            $this->exceptionList[] = $exceptionClassName;
        }
        return $this->exceptionList;
    }


    /**
     * @return string
     */
    public function getInvocationSignature(): string
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
    public function getMethodReturnType(): PHPMethodReturnType
    {
        return $this->methodReturnType;
    }


    /**
     * @return PHPParameter[]
     */
    public function getMethodParameterList(): array
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