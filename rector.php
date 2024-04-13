<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
//    ->withRules([
////        AddVoidReturnTypeWhereNoReturnRector::class,
//    ])
    ->withSets([
        __DIR__ . '/annotations-to-attributes.php'
    ]);
