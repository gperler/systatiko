<?php

declare(strict_types = 1);

namespace SystatikoTest\Functional;

use Civis\Common\File;
use Codeception\Util\Debug;
use Systatiko\Reader\PHPClass;
use Systatiko\Reader\PHPMethod;
use SystatikoTest\Functional\Asset\MethodReaderTestClass;

class MethodReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testMethodRead()
    {
        $file = new File(__DIR__ . "/Asset/MethodReaderTestClass.php");
        $phpClass = new PHPClass($file, MethodReaderTestClass::class);

        $this->assertNotNull($phpClass);

        $methodList = $phpClass->getPHPMethodList();

        $this->assertNotNull($methodList);
        $this->assertSame(6, sizeof($methodList));

        $this->testMethodOne($methodList[0]);
        $this->testMethodTwo($methodList[1]);

        $this->testMethodFour($methodList[3]);
        $this->testMethodFive($methodList[5]);

    }

    protected function testMethodOne(PHPMethod $method)
    {
        $this->assertSame("testMe", $method->getMethodName());
        $this->assertSame('$file, $array, $mixed', $method->getInvocationSignature());

        $parameterList = $method->getMethodParameterList();
        $this->assertSame(3, sizeof($parameterList));

        $annotation = $method->getMethodAnnotation('Systatiko\Annotation\FacadeExposition');
        $this->assertNotNull($annotation);
        $this->assertSame('test\namespace', $annotation->getNamespace());

        // @return Test[]|null
        $returnType = $method->getMethodReturnType();
        $this->assertNotNull($returnType);
        $this->assertTrue($returnType->canBeNull());
        $this->assertSame('array', $returnType->getSignatureType());
        $docBlockType = $returnType->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertNotNull($docBlockType->getClassName());
        $this->assertSame('Civis\Common\File', $docBlockType->getClassName()->getClassName());
        $this->assertSame('Test', $docBlockType->getClassName()->getAs());
        $this->assertSame('Test', $docBlockType->getClassName()->getClassShortName());
        $this->assertSame(true, $docBlockType->isArray());
        $this->assertSame(true, $docBlockType->canBeNull());
        $this->assertSame(false, $docBlockType->isVoid());

        //
        // param 0
        //
        $parameter0 = $parameterList[0];
        $parameterClassName = $parameter0->getClassName();
        $this->assertNotNull($parameterClassName);
        $this->assertSame('Test', $parameterClassName->getClassShortName());
        $this->assertSame('Test', $parameterClassName->getAs());
        $this->assertSame('Civis\Common\File', $parameterClassName->getClassName());
        $this->assertSame(null, $parameter0->getDefault());
        $this->assertSame('file', $parameter0->getName());
        $this->assertSame('Test $file', $parameter0->getSignatureSnippet());



        $docBlockType = $parameter0->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertNotNull($docBlockType->getClassName());
        $this->assertSame('Test', $docBlockType->getClassName()->getAs());
        $this->assertSame('Test', $docBlockType->getClassName()->getClassShortName());
        $this->assertSame('Civis\Common\File', $docBlockType->getClassName()->getClassName());

        $this->assertSame('Test', $docBlockType->getOriginal());
        $this->assertSame(null, $docBlockType->getTypeName());
        $this->assertSame(false, $docBlockType->isArray());
        $this->assertSame(false, $docBlockType->canBeNull());

        //
        // param 1
        //
        $parameter1 = $parameterList[1];
        $this->assertSame(null, $parameter1->getClassName());
        $this->assertSame('array', $parameter1->getName());
        $this->assertSame('array $array', $parameter1->getSignatureSnippet());
        $this->assertSame('array', $parameter1->getType());

        $docBlockType = $parameter1->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertSame('string[]', $docBlockType->getOriginal());
        $this->assertSame(null, $docBlockType->getClassName());
        $this->assertSame('string', $docBlockType->getTypeName());
        $this->assertSame(true, $docBlockType->isArray());
        $this->assertSame(false, $docBlockType->canBeNull());

        //
        // param 2

        $parameter2 = $parameterList[2];
        $this->assertSame(null, $parameter2->getClassName());
        $this->assertSame(null, $parameter2->getDefault());
        $this->assertSame('mixed', $parameter2->getName());
        $this->assertSame('$mixed = null', $parameter2->getSignatureSnippet());
        $this->assertSame(null, $parameter2->getType());

        $docBlockType = $parameter2->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertSame('mixed', $docBlockType->getOriginal());
        $this->assertSame(null, $docBlockType->getClassName());
        $this->assertSame('mixed', $docBlockType->getTypeName());
        $this->assertSame(false, $docBlockType->isArray());
        $this->assertSame(false, $docBlockType->canBeNull());

    }

    protected function testMethodTwo(PHPMethod $method)
    {
        $this->assertSame("testMeToo", $method->getMethodName());
        $this->assertSame('$typeList, $test, $array', $method->getInvocationSignature());

        $parameterList = $method->getMethodParameterList();
        $this->assertSame(3, sizeof($parameterList));

        // @return PHPType[]
        $returnType = $method->getMethodReturnType();
        $this->assertNotNull($returnType);
        $this->assertFalse($returnType->canBeNull());
        $this->assertSame("array", $returnType->getSignatureType());
        $docBlockType = $returnType->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertNotNull($docBlockType->getClassName());
        $this->assertSame('Systatiko\Reader\PHPType', $docBlockType->getClassName()->getClassName());
        $this->assertSame(null, $docBlockType->getClassName()->getAs());
        $this->assertSame('PHPType', $docBlockType->getClassName()->getClassShortName());
        $this->assertSame(true, $docBlockType->isArray());
        $this->assertSame(false, $docBlockType->canBeNull());
        $this->assertSame(false, $docBlockType->isVoid());

        //
        // param 0
        //
        // Signature array $typeList

        $parameter = $parameterList[0];
        $this->assertSame(null, $parameter->getClassName());
        $this->assertSame(null, $parameter->getDefault());
        $this->assertSame('typeList', $parameter->getName());
        $this->assertSame('array $typeList', $parameter->getSignatureSnippet());
        $this->assertSame('array', $parameter->getType());

        // @param PHPType[] $typeList
        $docBlockType = $parameter->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertNotNull($docBlockType->getClassName());
        $this->assertSame(null, $docBlockType->getClassName()->getAs());
        $this->assertSame('PHPType', $docBlockType->getClassName()->getClassShortName());
        $this->assertSame('Systatiko\Reader\PHPType', $docBlockType->getClassName()->getClassName());

        $this->assertSame('PHPType[]', $docBlockType->getOriginal());
        $this->assertSame(null, $docBlockType->getTypeName());
        $this->assertSame(true, $docBlockType->isArray());
        $this->assertSame(false, $docBlockType->canBeNull());


        //
        // param 1
        //
        // string $test = "null"
        $parameter0 = $parameterList[1];
        $this->assertSame(null, $parameter0->getClassName());
        $this->assertSame('null', $parameter0->getDefault());
        $this->assertSame('test', $parameter0->getName());
        $this->assertSame('string $test = "null"', $parameter0->getSignatureSnippet());
        $this->assertSame('string', $parameter0->getType());

        // string $test
        $docBlockType = $parameter0->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertNull($docBlockType->getClassName());
        $this->assertSame('string', $docBlockType->getOriginal());
        $this->assertSame('string', $docBlockType->getTypeName());
        $this->assertSame(false, $docBlockType->isArray());
        $this->assertSame(false, $docBlockType->canBeNull());

        //
        // param 2
        //
        // Signature array $array = null

        $parameter = $parameterList[2];
        $this->assertSame(null, $parameter->getClassName());
        $this->assertSame(null, $parameter->getDefault());
        $this->assertSame('array', $parameter->getName());
        $this->assertSame('array $array = null', $parameter->getSignatureSnippet());
        $this->assertSame('array', $parameter->getType());

        // @param Test[]|null $array
        $docBlockType = $parameter->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $this->assertNotNull($docBlockType->getClassName());
        $this->assertSame('Test', $docBlockType->getClassName()->getAs());
        $this->assertSame('Test', $docBlockType->getClassName()->getClassShortName());
        $this->assertSame('Civis\Common\File', $docBlockType->getClassName()->getClassName());

        $this->assertSame('Test[]|null', $docBlockType->getOriginal());
        $this->assertSame(null, $docBlockType->getTypeName());
        $this->assertSame(true, $docBlockType->isArray());
        $this->assertSame(true, $docBlockType->canBeNull());


    }

    protected function testMethodFour(PHPMethod $method)
    {
        $parameterList = $method->getMethodParameterList();

        $this->assertSame(1, sizeof($parameterList));

        $parameter = $parameterList[0];

        $className = $parameter->getClassName();
        $this->assertNotNull($className);
        $this->assertSame('SystatikoTest\Functional\Asset\ClassReaderTestClass', $className->getClassName());
        $this->assertSame('ClassReaderTestClass', $className->getClassShortName());
        $this->assertSame('SystatikoTest\Functional\Asset', $className->getNamespaceName());

        $docBlockType = $parameter->getDocBlockType();
        $this->assertNotNull($docBlockType);
        $className = $docBlockType->getClassName();
        $this->assertNotNull($className);
        $this->assertSame('SystatikoTest\Functional\Asset\ClassReaderTestClass', $className->getClassName());
        $this->assertSame('ClassReaderTestClass', $className->getClassShortName());
        $this->assertSame('SystatikoTest\Functional\Asset', $className->getNamespaceName());


        $returnType = $method->getMethodReturnType();


    }


    protected function testMethodFive(PHPMethod $method) {
        $returnType = $method->getMethodReturnType();

        $this->assertTrue($returnType->canBeNull());
        $this->assertSame('string', $returnType->getFullyQualifiedName());

        $exceptionList = $method->getThrownExceptionList();

        $this->assertSame($exceptionList[0]->getClassName(), 'SystatikoTest\Functional\Asset\TestException1');
        $this->assertSame($exceptionList[1]->getClassName(), 'SystatikoTest\Functional\Asset\TestException2');
        $this->assertSame($exceptionList[2]->getClassName(), 'Systatiko\Exception\EventNotDefinedException');
    }
}