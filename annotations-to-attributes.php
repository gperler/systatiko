<?php

declare (strict_types=1);

namespace test;

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Systatiko\Annotation\Configuration;
use Systatiko\Annotation\EventHandler;
use Systatiko\Annotation\ExposeInAllFactories;
use Systatiko\Annotation\FacadeExposition;
use Systatiko\Annotation\Factory;
use Systatiko\Contract\Event;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute(Configuration::class),
        new AnnotationToAttribute(Event::class),
        new AnnotationToAttribute(EventHandler::class),
        new AnnotationToAttribute(ExposeInAllFactories::class),
        new AnnotationToAttribute(FacadeExposition::class),
        new AnnotationToAttribute(Factory::class),
    ]);
};
