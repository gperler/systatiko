<?php

namespace Systatiko\Reader;

use Civis\Common\ArrayUtil;
use Civis\Common\File;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use ReflectionException;

/**
 * @author Gregor Müller
 */
class PHPClass
{

    /**
     * @var File
     */
    private $file;

    /**
     * @var ExtendedReflectionClass
     */
    private $reflectClass;

    /**
     * @var PHPClassName
     */
    private $className;

    /**
     * @var PHPClassName[]
     */
    private $usedClassNameList;

    /**
     * @var string
     */
    private $errorMessage;


    /**
     * PHPClass constructor.
     *
     * @param File $file
     * @param string $className
     *
     * @throws ReflectionException
     */
    public function __construct(File $file, string $className)
    {
        $this->file = $file;
        $this->className = new PHPClassName($className);
        $this->usedClassNameList = [];
        $this->errorMessage = null;
        $this->reflect($className);
    }


    /**
     * @param string $className
     *
     * @throws ReflectionException
     */
    private function reflect(string $className): void
    {
        include_once($this->file->getAbsoluteFileName());

        $this->reflectClass = new ExtendedReflectionClass($className);

        foreach ($this->reflectClass->getUseStatementList() as $statement) {
            $className = ArrayUtil::getFromArray($statement, "class");
            $as = ArrayUtil::getFromArray($statement, "as");

            $as = ($as !== $className) ? $as : null;
            $this->usedClassNameList[] = new PHPClassName($className, $as);
        }
    }


    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->errorMessage !== null;
    }


    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }


    /**
     * @return string
     */
    public function getClassShortName()
    {
        return $this->className->getClassShortName();
    }


    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className->getClassName();
    }


    /**
     * @return string
     */
    public function getNamespaceName()
    {
        return $this->className->getNamespaceName();
    }


    /**
     * @return PHPClassName[]
     */
    public function getUsedClassNameList()
    {
        return $this->usedClassNameList;
    }


    /**
     * @param string $interfaceName
     *
     * @return bool
     */
    public function implementsInterface(string $interfaceName)
    {
        return $this->reflectClass->implementsInterface($interfaceName);
    }


    /**
     * @param string $className
     *
     * @return bool
     */
    public function isSubclassOf(string $className): bool
    {
        return $this->reflectClass->isSubclassOf($className);
    }


    /**
     * @param $annotationName
     *
     * @return null|mixed
     */
    public function getClassAnnotation($annotationName): mixed
    {
        $annotationClass = new PHPClassName($annotationName);
        new $annotationName;
        $reader = new SimpleAnnotationReader();
        $reader->addNamespace($annotationClass->getNamespaceName());
        return $reader->getClassAnnotation($this->reflectClass, $annotationName);
    }


    /**
     * @return PHPMethod[]
     */
    public function getPHPMethodList()
    {
        $methodList = [];
        foreach ($this->reflectClass->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === $this->getClassName() && !$method->isConstructor()) {
                $methodList[] = new PHPMethod($this, $method);
            }
        }
        return $methodList;
    }


    /**
     * @return PHPMethod
     */
    public function getConstructorMethod()
    {
        $reflectMethod = $this->reflectClass->getConstructor();
        if ($reflectMethod === null) {
            return null;
        }
        return new PHPMethod($this, $reflectMethod);
    }


    /**
     * @param string|null $shortName
     *
     * @return PHPClassName|null
     */
    public function getClassNameForShortName(string $shortName = null)
    {
        if ($shortName === null) {
            return null;
        }

        if ($shortName === $this->getClassShortName()) {
            return $this->className;
        }

        foreach ($this->usedClassNameList as $usedClassName) {
            if ($usedClassName->getClassShortName() === $shortName) {
                return $usedClassName;
            }
        }

        return $this->getSameNamespaceClass($shortName);
    }


    /**
     * @param string|null $className
     *
     * @return string
     */
    public function getShortNameForClassName(string $className = null): ?string
    {
        if ($className === null) {
            return null;
        }
        foreach ($this->usedClassNameList as $usedClassName) {
            if ($usedClassName->getClassName() === $className) {
                return $usedClassName->getAs();
            }
        }
        return null;
    }


    /**
     * @param string $classShortName
     *
     * @return null|PHPClassName
     */
    public function getSameNamespaceClass(string $classShortName)
    {
        $dirName = $this->file->getDirName();

        $possibleFileName = $dirName . DIRECTORY_SEPARATOR . $classShortName . '.php';
        $possibleFile = new File($possibleFileName);

        if (!$possibleFile->exists()) {
            return null;
        }

        return new PHPClassName($this->getNamespaceName() . "\\" . $classShortName);
    }


    /**
     * @return string
     */
    public function getClassContent()
    {
        return $this->file->getContents();
    }


    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

}