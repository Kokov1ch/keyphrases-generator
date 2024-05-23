<?php

declare(strict_types=1);

namespace App\Service\Sanitizer;

final class PermutationSanitizerService implements SanitizerInterface
{
    public function process(array $data): array
    {
        foreach ($data as &$permutation) {
            $this->sanitizePermutation($permutation);
        }

        return $this->removeDuplicates($this->excludeSameKeys($data));
    }

    private function sanitizePermutation(array &$permutation): void
    {
        $minusWords = $this->extractMinusWords($permutation);
        $permutation = array_filter($permutation, static fn ($word): bool => $word !== '');
        $permutation = array_merge($permutation, $minusWords);
    }

    private function extractMinusWords(array &$permutation): array
    {
        $minusWords = [];
        foreach ($permutation as &$word) {
            $subWords = explode(' ', $word);
            foreach ($subWords as $key => &$subWord) {
                if ($subWord[0] === '-') {
                    $minusWords[] = $subWord;
                    unset($subWords[$key]);
                } elseif ($subWord === '') {
                    unset($subWords[$key]);
                }

                $subWord = trim($subWord);
            }

            $word = implode(' ', $subWords);
        }

        return $minusWords;
    }

    private function removeDuplicates(array $permutations): array
    {
        $uniquePermutations = [];
        foreach ($permutations as $permutation) {
            $phrase = implode(' ', $permutation);
            if (!in_array($phrase, $uniquePermutations, true)) {
                $uniquePermutations[] = $phrase;
            }
        }

        return array_map(static fn ($phrase): array => explode(' ', $phrase), $uniquePermutations);
    }

    private function excludeSameKeys(array $permutations): array
    {
        foreach ($permutations as &$permutation) {
            $permutation = array_unique($permutation);
        }

        return $permutations;
    }
}
