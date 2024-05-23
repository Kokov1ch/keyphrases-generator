<?php

declare(strict_types=1);

namespace App\Service\Preprocessor;

interface PreprocessorInterface
{
    public function process(array $data): array;
}
