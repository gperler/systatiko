<?php

declare(strict_types=1);

namespace SystatikoTest\End2End\Asset\Component2\Model;

use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use SystatikoTest\End2End\Asset\Component1\Entity\InjectContext;

#[FacadeExposition(namespace: 'SystatikoTest\End2End\Asset\Generated\Component2')]
class InjectContextService
{

    /**
     * @var InjectContext
     */
    private $injectContext;

    /**
     * @param InjectContext $injectContext
     */
    #[Factory(namespace: 'SystatikoTest\End2End\Asset\Generated\Component2', singleton: true)] // InjectContextService constructor.
    public function __construct(InjectContext $injectContext)
    {
        $this->injectContext = $injectContext;
    }


    /**
     * @return string
     */
    #[FacadeExposition]
    public function injectedSayHello(): string
    {
        return $this->injectContext->sayHello();
    }

}