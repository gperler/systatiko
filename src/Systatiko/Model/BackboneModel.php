<?php

declare(strict_types=1);

namespace Systatiko\Model;

use Systatiko\Annotation\ExposeInAllFactories;
use Systatiko\Reader\PHPClass;
use Systatiko\Reader\PHPMethod;

class BackboneModel
{


    /**
     * @var PHPClass
     */
    private $phpClass;

    /**
     * @var PHPMethod[]
     */
    private $exposeList;

    /**
     * BackboneModel constructor.
     *
     * @param PHPClass $phpClass
     */
    public function __construct(PHPClass $phpClass)
    {
        $this->phpClass = $phpClass;
        $this->exposeList = [];
        $this->findExposeAll();
    }

    /**
     *
     */
    private function findExposeAll()
    {
        foreach ($this->phpClass->getPHPMethodList() as $phpMethod) {
            $this->analyzeMethod($phpMethod);
        }
    }

    /**
     * @param PHPMethod $method
     */
    private function analyzeMethod(PHPMethod $method)
    {
        $annotation = $method->getMethodAnnotation(ExposeInAllFactories::class);
        if ($annotation === null) {
            return;
        }
        $this->exposeList[] = $method;
    }

    /**
     * @return PHPMethod[]
     */
    public function getExposeList(): array
    {
        return $this->exposeList;
    }
}