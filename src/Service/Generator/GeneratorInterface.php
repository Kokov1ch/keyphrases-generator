<?php

declare(strict_types=1);

namespace App\Service\Generator;

interface GeneratorInterface
{
    public function generate(array $data): array;
}
