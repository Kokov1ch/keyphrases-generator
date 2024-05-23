<?php

declare(strict_types=1);

namespace App\Service\Generator;

final class PermutationGeneratorService implements GeneratorInterface
{
    public function generate(array $data): array
    {
        return $this->getPermutations($data, []);
    }

    private function getPermutations(array $words, array $variant, int $index = 0): array
    {
        if ($index > count($words) - 1) {
            return [$variant];
        }

        $line = $words[$index];

        $results = [];
        foreach ($line as $word) {
            $tmpVariant = array_merge($variant, [$word]);
            $results = array_merge($results, $this->getPermutations($words, $tmpVariant, $index + 1));
        }

        return $results;
    }
}
