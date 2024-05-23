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
        $processedLine = array_map(fn (string $word): string => $this->correctWord($word), $line);

        return array_values($this->removeDuplicates(array_filter($processedLine, static fn ($word): bool => $word !== '')));
    }

    private function replaceSymbols(string $word): string
    {
        return str_replace(['!', '?', '.', '-', ',', ';', ':'], ' ', $word);
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
            $lexeme[0] . $this->replaceSymbols(substr($lexeme, 1)) : '';

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

                        if (str_contains($wordToCheck, $wordToMinus) && !in_array($wordToCheck[0], ['-', '?', '!'], true)) {
                            $subWords = explode(' ', $wordToCheck);
                            foreach ($subWords as $subWord) {
                                if (!str_contains($buff[$strKey][$wordKey], $subWord)) {
                                    $buff[$strKey][$wordKey] .= ' -' . $subWord . ' ';
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
