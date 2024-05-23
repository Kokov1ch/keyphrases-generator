<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests/unit',
    ])
    ->withRules([TypedPropertyFromStrictConstructorRector::class, ])
    ->withPreparedSets(codeQuality: true, codingStyle: true, typeDeclarations: true, privatization: true, naming: true);