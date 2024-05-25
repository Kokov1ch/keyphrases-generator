<?php

declare(strict_types=1);

namespace App\Service\Preprocessor;

final class PreprocessorService implements PreprocessorInterface
{
    public function process(array $data): array
    {
        return $this->addMinusWords(array_map(fn (array $line): array => $this->processLine($line), $data));
    }

    private function processLine(array $line): array
    {
        $processedLine = array_map(fn (string $word): string => $this->correctWord(trim($word)), $line);

        return array_values($this->removeDuplicates(array_filter($processedLine, static fn ($word): bool => $word !== '')));
    }

    private function replaceSymbols(string $word): string
    {
        return trim(preg_replace('/[^a-zA-Z0-9А-Яа-я]/u', ' ', $word));
    }

    private function removeDuplicates(array $data): array
    {
        return array_unique($data);
    }

    private function correctWord(string $word): string
    {
        $lexemes = array_map(fn (string $lexeme): string => $this->correctLexeme($lexeme), explode(' ', $word));

        return implode(' ', array_values(array_filter($lexemes, static fn ($lexeme): bool => $lexeme !== '')));
    }

    private function correctLexeme(string $lexeme): string
    {
        $lexeme = preg_match('/^[-!+]*[А-Яа-яA-Za-z0-9]+/', $lexeme) ?
            mb_substr($lexeme, 0, 1) . $this->replaceSymbols(mb_substr($lexeme, 1)) :
            '';

        if (mb_strlen($lexeme) === 0) {
            return '';
        }

        if (mb_strlen($lexeme) > 0 && mb_strlen($lexeme) <= 2) {
            $lexeme = '+' . $lexeme;
        }

        return trim($lexeme);
    }

    private function addMinusWords(array $data): array
    {
        $buff = $data;

        foreach ($data as $strKey => $lineToMinus) {
            foreach ($lineToMinus as $wordKey => $wordToMinus) {
                foreach ($data as $lineToCheck) {
                    foreach ($lineToCheck as $wordToCheck) {
                        if ($wordToCheck === $wordToMinus) {
                            continue;
                        }

                        if (str_contains($wordToCheck, $wordToMinus) && !in_array(mb_substr($wordToCheck, 0, 1), ['-', '?', '!'], true)) {
                            $subWords = explode(' ', $wordToCheck);
                            foreach ($subWords as $subWord) {
                                if (!str_contains($buff[$strKey][$wordKey], $subWord) && !in_array(mb_substr($subWord, 0, 1), ['-', '?', '!'], true)) {
                                    $buff[$strKey][$wordKey] .= ' -' . trim($subWord);
                                }
                            }
                        }

                        $buff[$strKey][$wordKey] = trim($buff[$strKey][$wordKey]);
                    }
                }
            }
        }

        return $buff;
    }
}
