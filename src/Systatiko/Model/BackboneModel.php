<?php

declare(strict_types=1);

namespace Systatiko\Model;

use Systatiko\Annotation\ExposeInAllFactories;
use Systatiko\Reader\PHPClass;
use Systatiko\Reader\PHPMethod;

class BackboneModel
{


    /**
     * @var PHPMethod[]
     */
    private $exposeList;

    /**
     * BackboneModel constructor.
     */
    public function __construct(private readonly PHPClass $phpClass)
    {
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