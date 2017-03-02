<?php

declare(strict_types = 1);

namespace Systatiko\Model;

use Systatiko\Reader\PHPClass;
use Systatiko\Reader\PHPMethod;

class FacadeLocatorModel
{

    const EXPOSE_IN_ALL_ANNOTATION_NAME = 'Systatiko\Annotation\ExposeInAllFactories';

    /**
     * @var PHPClass
     */
    protected $phpClass;

    /**
     * @var PHPMethod[]
     */
    protected $exposeList;

    /**
     * FacadeLocatorModel constructor.
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
    protected function findExposeAll()
    {
        foreach ($this->phpClass->getPHPMethodList() as $phpMethod) {
            $this->analyzeMethod($phpMethod);
        }
    }

    /**
     * @param PHPMethod $method
     */
    protected function analyzeMethod(PHPMethod $method)
    {
        $annotation = $method->getMethodAnnotation(self::EXPOSE_IN_ALL_ANNOTATION_NAME);
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