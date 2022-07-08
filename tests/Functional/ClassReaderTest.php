<?php

declare(strict_types = 1);

namespace SystatikoTest\Functional;

use Systatiko\Reader\PHPClass;
use Civis\Common\File;
use SystatikoTest\Functional\Asset\ClassReaderTestClass;

class ClassReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testClassRead()
    {

        $file = new File(__DIR__ . "/Asset/ClassReaderTestClass.php");
        $phpClass = new PHPClass($file, ClassReaderTestClass::class);

        $this->assertTrue($phpClass->implementsInterface('\Serializable'));
        $this->assertTrue($phpClass->isSubclassOf('\DateTime'));

        $this->assertSame(ClassReaderTestClass::class, $phpClass->getClassName());
        $this->assertSame('ClassReaderTestClass', $phpClass->getClassShortName());
        $this->assertSame('SystatikoTest\Functional\Asset', $phpClass->getNamespaceName());


        $methodList = $phpClass->getPHPMethodList();
        $this->assertSame(2, sizeof($methodList));

        $constructor = $phpClass->getConstructorMethod();
        $this->assertNotNull($constructor);


        // use class name resolution
        $className = $phpClass->getClassNameForShortName("Test");
        $this->assertNotNull($className);
        $this->assertSame('Civis\Common\File', $className->getClassName());

        $className = $phpClass->getClassNameForShortName("PHPClass");
        $this->assertNotNull($className);
        $this->assertSame('Systatiko\Reader\PHPClass', $className->getClassName());

        $this->assertSame(null, $phpClass->getClassNameForShortName("Anything"));

        $annotation = $phpClass->getClassAnnotation('Systatiko\Annotation\Event');
        $this->assertNotNull($annotation);
        $this->assertSame('test\namespace', $annotation->getNamespace());

        $this->assertNotNull($phpClass->getClassContent());

    }
}