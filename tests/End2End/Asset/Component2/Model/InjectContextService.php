<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component2\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Component1\Entity\InjectContext;

/**
 * @FacadeExposition(namespace="SystatikoTest\End2End\Asset\Generated\Component2")
 */
class InjectContextService
{

    /**
     * @var InjectContext
     */
    private $injectContext;

    /**
     * @Factory(namespace="SystatikoTest\End2End\Asset\Generated\Component2", singleton=true)
     *
     * InjectContextService constructor.
     * @param InjectContext $injectContext
     */
    public function __construct(InjectContext $injectContext)
    {
        $this->injectContext = $injectContext;
    }


    /**
     * @FacadeExposition()
     * @return string
     */
    public function injectedSayHello(): string
    {
        return $this->injectContext->sayHello();
    }

}