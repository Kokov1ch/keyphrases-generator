<?php

declare(strict_types=1);

namespace App\Service\Sanitizer;

interface SanitizerInterface
{
    public function process(array $data): array;
}
